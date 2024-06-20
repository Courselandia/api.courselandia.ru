<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Imports\Parsers;

use Generator;
use App\Modules\School\Enums\School;
use App\Modules\Course\Entities\ParserCourse;
use App\Modules\Course\Enums\Currency;
use App\Modules\Course\Imports\ParserYml;
use App\Modules\Direction\Enums\Direction;
use App\Modules\Course\Enums\Duration;

/**
 * Парсинг курсов KARPOV.COURSES.
 */
class ParserKarpovcourses extends ParserYml
{
    /**
     * Вернет школу.
     *
     * @return School Школа.
     */
    public function getSchool(): School
    {
        return School::KARPOV_COURSES;
    }

    /**
     * Вернет массив сопоставлений между названиями категорий из источника и их направлениями в системе.
     *
     * @return array<string, Direction> Массив, где ключ это название категории из источника, а значение, это направление в системе.
     */
    public function getDirections(): array
    {
        return [
            'Веб-разработка' => Direction::PROGRAMMING,
            'Машинное обучение' => Direction::PROGRAMMING,
            'Аналитика данных' => Direction::ANALYTICS,
            'Работа с данными' => Direction::ANALYTICS,
            'Data Science' => Direction::PROGRAMMING,
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
            $course->url = $offer['url'];
            $course->text = $offer['description'];
            $course->currency = Currency::RUB;
            $course->image = $offer['picture'] ?? null;
            $course->status = $offer['attributes']['available'] === 'true';
            $course->direction = $offer['direction'] ?? null;

            if (isset($offer['params']['Цена по скидке']['value']) && $offer['params']['Цена по скидке']['value']) {
                $course->price = $offer['params']['Цена по скидке']['value'];
                $course->price_old = $offer['price'];
            } else {
                $course->price = $offer['price'];
            }

            if (isset($offer['params']['Ежемесячная цена']['value']) && $offer['params']['Ежемесячная цена']['value']) {
                $course->price_recurrent = $offer['params']['Ежемесячная цена']['value'];
            }

            if (isset($offer['params']['Продолжительность']['value']) && $offer['params']['Продолжительность']['value']) {
                $course->duration = $offer['params']['Продолжительность']['value'];
                $course->duration_unit = Duration::MONTH;
            }

            yield $course;
        }
    }
}
