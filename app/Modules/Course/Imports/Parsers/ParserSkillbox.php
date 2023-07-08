<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Imports\Parsers;

use App\Modules\Course\Enums\Duration;
use App\Modules\School\Enums\School;
use Generator;
use App\Modules\Course\Entities\ParserCourse;
use App\Modules\Course\Enums\Currency;
use App\Modules\Course\Imports\ParserYml;
use App\Modules\Direction\Enums\Direction;

/**
 * Парсинг курсов Skillbox.
 */
class ParserSkillbox extends ParserYml
{
    /**
     * Вернет школу.
     *
     * @return School Школа.
     */
    public function getSchool(): School
    {
        return School::SKILLBOX;
    }

    /**
     * Вернет массив сопоставлений между названиями категорий из источника и их направлениями в системе.
     *
     * @return array<string, Direction> Массив, где ключ это название категории из источника, а значение, это направление в системе.
     */
    public function getDirections(): array
    {
        return [
            'Для бизнеса' => Direction::BUSINESS,
            'Бизнес-школа' => Direction::BUSINESS,
            'Английский язык' => Direction::OTHER,
            'Маркетинг' => Direction::MARKETING,
            'Дизайн' => Direction::DESIGN,
            'Игры' => Direction::GAMES,
            'Программирование' => Direction::PROGRAMMING,
            'Управление' => Direction::BUSINESS,
            'Кино и Музыка' => Direction::OTHER,
            'Общее развитие' => Direction::OTHER,
            'Психология' => Direction::OTHER,
            'Инженерия' => Direction::OTHER,
            'Архитектура' => Direction::OTHER,
            'Другое' => Direction::OTHER,
            'Высшее образование' => Direction::OTHER,
        ];
    }

    /**
     * Получение курса.
     *
     * @return Generator<ParserCourse> Вернет один считанный курс.
     */
    public function read(): Generator
    {
        foreach ($this->getOffers() as $offer) {
            $course = new ParserCourse();
            $course->school = $this->getSchool();
            $course->uuid = $offer['attributes']['id'];
            $course->name = $offer['name'];
            $course->text = $offer['description'];
            $course->status = $offer['attributes']['available'] === 'true';
            $course->url = $offer['url'];
            $course->image = $offer['picture'] ?? null;
            $course->price = $offer['price'];
            $course->price_recurrent = $offer['credit_price'] ?? null;
            $course->currency = Currency::RUB;
            $course->direction = $offer['direction'];
            $course->employment = (bool)$offer['with_employment'];

            if (isset($offer['oldprice']) && $offer['oldprice']) {
                $course->price_old = $offer['oldprice'];
            }

            if (isset($offer['duration'])) {
                $course->duration = $offer['duration']['value'];
                $course->duration_unit = $this->getDurationUnit($offer['duration']['attributes']['unit']);

                if (!$course->duration_unit) {
                    $this->addError(
                        $this->getSchool()->getLabel()
                        . ' | ' . $offer['name']
                        . ' | Не удалось получить единицу продолжительности: "' . $offer['duration']['attributes']['unit'] . '".'
                    );
                }
            }

            yield $course;
        }
    }

    /**
     * Получить единицу измерения продолжительности.
     *
     * @param string $duration Продолжительность из источника.
     *
     * @return Duration|null Вернет единицу измерения продолжительности курса.
     */
    private function getDurationUnit(string $duration): ?Duration
    {
        if ($duration === 'year') {
            return Duration::YEAR;
        } elseif ($duration === 'month') {
            return Duration::MONTH;
        } elseif ($duration === 'week') {
            return Duration::WEEK;
        } elseif ($duration === 'day') {
            return Duration::DAY;
        }

        return null;
    }
}
