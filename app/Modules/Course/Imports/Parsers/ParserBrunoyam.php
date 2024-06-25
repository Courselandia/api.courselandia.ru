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
 * Парсинг курсов Бруноям
 */
class ParserBrunoyam extends ParserYml
{
    /**
     * Вернет школу.
     *
     * @return School Школа.
     */
    public function getSchool(): School
    {
        return School::BRUNOYAM;
    }

    /**
     * Вернет массив сопоставлений между названиями категорий из источника и их направлениями в системе.
     *
     * @return array<string, Direction> Массив, где ключ это название категории из источника, а значение, это направление в системе.
     */
    public function getDirections(): array
    {
        return [
            'Нейросети' => Direction::PROGRAMMING,
            'Курсы программирования' => Direction::PROGRAMMING,
            'Курсы интернет-маркетинга' => Direction::MARKETING,
            'Курсы дизайна' => Direction::DESIGN,
            'Курсы аналитики данных' => Direction::ANALYTICS,
            'Онлайн-курсы' => Direction::OTHER,
            'Менеджмент' => Direction::BUSINESS,
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
            $course->price = $offer['price'];
            $course->currency = Currency::RUB;
            $course->image = $offer['picture'] ?? null;
            $course->status = $offer['attributes']['available'] === 'true';
            $course->direction = $offer['direction'];

            if (
                isset($offer['params']['Продолжительность обучения, недель']['value']) &&
                $offer['params']['Продолжительность обучения, недель']['value'] &&
                intval($offer['params']['Продолжительность обучения, недель']['value'])
            ) {
                $course->duration = intval($offer['params']['Продолжительность обучения, недель']['value']);
                $course->duration_unit = Duration::WEEK;
            }

            if (isset($offer['params']['Количество занятий']['value']) && $offer['params']['Количество занятий']['value']) {
                $course->lessons_amount = $offer['params']['Количество занятий']['value'];
            }

            yield $course;
        }
    }
}
