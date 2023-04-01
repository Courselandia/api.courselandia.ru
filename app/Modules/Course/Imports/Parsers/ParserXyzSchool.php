<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Imports\Parsers;

use Generator;
use App\Modules\Course\Enums\Duration;
use App\Modules\School\Enums\School;
use App\Modules\Course\Entities\ParserCourse;
use App\Modules\Course\Enums\Currency;
use App\Modules\Course\Imports\ParserYml;
use App\Modules\Direction\Enums\Direction;

/**
 * Парсинг курсов XYZ School.
 */
class ParserXyzSchool extends ParserYml
{
    /**
     * Вернет школу.
     *
     * @return School Школа.
     */
    public function getSchool(): School
    {
        return School::XYZ_SCHOOL;
    }

    /**
     * Вернет массив сопоставлений между названиями категорий из источника и их направлениями в системе.
     *
     * @return array<string, Direction> Массив, где ключ это название категории из источника, а значение, это направление в системе.
     */
    public function getDirections(): array
    {
        return [
            'Игровой дизайн' => Direction::DESIGN,
            'Разработка игр на Unity' => Direction::GAMES,
            'Разработка игр на Unreal Engine' => Direction::GAMES,
            '3D-графика' => Direction::DESIGN,
            '2D-графика' => Direction::DESIGN,
            'Разное' => Direction::OTHER,
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
            $course->url = $offer['url'];
            $course->image = $offer['picture'] ?? null;
            $course->price = $offer['price'];
            $course->currency = Currency::RUB;
            $course->direction = $offer['direction'];
            $course->name = $offer['name'];
            $course->price_old = (isset($offer['oldprice']) && $offer['oldprice'] !== 'None') ? $offer['oldprice'] : null;
            $course->text = $offer['description'];
            $course->price_recurrent = (isset($offer['credit_price']) && $offer['credit_price'] !== 'None') ? $offer['credit_price'] : null;
            $course->duration = $offer['params']['Продолжительность']['value'] ?? null;

            if (isset($offer['params']['Продолжительность']['unit']) || isset($offer['params']['Продолжительность']['value'])) {
                $course->duration_unit = isset($offer['params']['Продолжительность']['unit'])
                    ? $this->getDurationUnit($offer['params']['Продолжительность']['unit'])
                    : Duration::MONTH;
            }

            $course->status = $offer['attributes']['available'] === 'true';
            $course->image = $offer['picture'] ?? null;

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
        if ($duration === 'год') {
            return Duration::YEAR;
        } elseif ($duration === 'месяц') {
            return Duration::MONTH;
        } elseif ($duration === 'неделя') {
            return Duration::WEEK;
        } elseif ($duration === 'день') {
            return Duration::DAY;
        }

        return null;
    }
}
