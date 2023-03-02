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
 * Парсинг курсов Нетологии.
 */
class ParserNetology extends ParserYml
{
    /**
     * Вернет школу.
     *
     * @return School Школа.
     */
    public function getSchool(): School
    {
        return School::NETOLOGIA;
    }

    /**
     * Вернет массив сопоставлений между названиями категорий из источника и их направлениями в системе.
     *
     * @return array<string, Direction> Массив, где ключ это название категории из источника, а значение, это направление в системе.
     */
    public function getDirections(): array
    {
        return [
            'Онлайн Открытое занятие HR' => Direction::OTHER,
            'Онлайн Открытое занятие Программирование' => Direction::PROGRAMMING,
            'Онлайн Курс Маркетинг' => Direction::PROGRAMMING,
            'Онлайн Курс Бизнес и управление' => Direction::BUSINESS,
            'Онлайн Курс Дизайн и UX' => Direction::DESIGN,
            'Онлайн Курс Программирование' => Direction::PROGRAMMING,
            'Онлайн Курс Аналитика' => Direction::ANALYTICS,
            'Онлайн Курс HR' => Direction::BUSINESS,
            'Онлайн Открытое занятие Маркетинг' => Direction::MARKETING,
            'Онлайн Открытое занятие Аналитика' => Direction::ANALYTICS,
            'Видеокурсы' => Direction::DESIGN,
            'Онлайн Курс B2B' => Direction::ANALYTICS,
            'Онлайн Курс EdMarket' => Direction::MARKETING,
            'Онлайн Модульный набор EdMarket' => Direction::MARKETING,
            'Онлайн Курс Хобби' => Direction::OTHER,
            'Онлайн Модульный набор Маркетинг' => Direction::MARKETING,
            'Онлайн Модульный набор Дизайн и UX' => Direction::DESIGN,
            'Онлайн Модульный набор Программирование' => Direction::PROGRAMMING,
            'Онлайн Модульный набор Аналитика' => Direction::ANALYTICS,
            'Онлайн Модульный набор Бизнес и управление' => Direction::BUSINESS,
            'Онлайн Курс Прочее' => Direction::OTHER,
            'Онлайн Открытое занятие Дизайн и UX' => Direction::DESIGN,
            'Онлайн Открытое занятие Бизнес и управление' => Direction::BUSINESS,
            'Онлайн Курс Лайфстайл и хобби' => Direction::OTHER,
            'Онлайн Модульный набор Высшее образование' => Direction::BUSINESS,
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
            $course->header = $offer['name'];
            $course->text = $offer['description'];
            $course->status = $offer['attributes']['available'] === 'true';
            $course->url = $offer['url'];
            $course->image = $offer['picture'] ?? null;
            $course->price = $offer['price'];
            $course->price_recurrent = $offer['min_credit_payment_sum'] ?? null;
            $course->currency = Currency::RUB;
            $course->direction = $offer['direction'];

            if (isset($offer['oldprice']) && $offer['oldprice']) {
                $course->price_old = $offer['oldprice'];
            }

            if (isset($offer['params']['Продолжительность']) && $offer['params']['Продолжительность']) {
                $course->duration = $this->getDuration($offer['params']['Продолжительность']);
                $course->duration_unit = $this->getDurationUnit($offer['params']['Продолжительность']);

                if (!$course->duration_unit) {
                    $this->addError(
                        $this->getSchool()->getLabel()
                        . ' | ' . $offer['name']
                        . ' | Не удалось получить единицу продолжительности: "' . $offer['params']['Продолжительность'] . '".'
                    );
                }
            }

            if (isset($offer['params']['Количество занятий']) && $offer['params']['Количество занятий']) {
                $course->lessons_amount = $this->getLessonsAmount($offer['params']['Количество занятий']);
            }

            yield $course;
        }
    }

    /**
     * Получить единицу измерения продолжительности.
     *
     * @param string $duration Продолжительность из источника.
     *
     * @return int Вернет продолжительность.
     */
    public function getDuration(string $duration): int
    {
        [$value] = explode(' ', $duration);

        return (int)$value;
    }

    /**
     * Получить единицу измерения продолжительности.
     *
     * @param string $duration Продолжительность из источника.
     *
     * @return Duration|null Вернет единицу измерения продолжительности курса.
     */
    public function getDurationUnit(string $duration): ?Duration
    {
        [, $duration] = explode(' ', $duration);
        $duration = trim($duration);

        if (in_array($duration, ['день', 'дня', 'дней'])) {
            return Duration::DAY;
        } elseif (in_array($duration, ['неделя', 'недели', 'недель'])) {
            return Duration::WEEK;
        } elseif (in_array($duration, ['месяц', 'месяца', 'месяцев'])) {
            return Duration::MONTH;
        } elseif (in_array($duration, ['год', 'года', 'лет'])) {
            return Duration::YEAR;
        }

        return null;
    }

    /**
     * Получить количество уроков.
     *
     * @param string $value Количество уроков из источника.
     *
     * @return int|null Вернет количество уроков.
     */
    public function getLessonsAmount(string $value): ?int
    {
        [$value] = explode(' ', $value);

        return (int)$value;
    }
}
