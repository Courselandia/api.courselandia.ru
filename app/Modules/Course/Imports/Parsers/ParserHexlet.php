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
 * Парсинг курсов Hexlet.
 */
class ParserHexlet extends ParserYml
{
    /**
     * Вернет школу.
     *
     * @return School Школа.
     */
    public function getSchool(): School
    {
        return School::HEXLET;
    }

    /**
     * Вернет массив сопоставлений между названиями категорий из источника и их направлениями в системе.
     *
     * @return array<string, Direction> Массив, где ключ это название категории из источника, а значение, это направление в системе.
     */
    public function getDirections(): array
    {
        return [
            'javascript' => Direction::PROGRAMMING,
            'php' => Direction::PROGRAMMING,
            'python' => Direction::PROGRAMMING,
            'html' => Direction::PROGRAMMING,
            'java' => Direction::PROGRAMMING,
            'ruby' => Direction::PROGRAMMING,
            'sql' => Direction::PROGRAMMING,
            'shell' => Direction::PROGRAMMING,
            'other' => Direction::PROGRAMMING,
            'golang' => Direction::PROGRAMMING,
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
            $course->price = $offer['params']['Цена по скидке']['value'];
            $course->price_old = $offer['price'];
            $course->currency = Currency::RUB;
            $course->direction = $offer['direction'];
            $course->employment = $offer['params']['С трудоустройством']['value'];

            if (isset($offer['params']['Продолжительность']['value']) && $offer['params']['Продолжительность']['value']) {
                $course->duration = $offer['params']['Продолжительность']['value'];
                $course->duration_unit = Duration::MONTH;
            } else {
                $course->duration = null;
                $course->duration_unit = null;
            }

            yield $course;
        }
    }
}
