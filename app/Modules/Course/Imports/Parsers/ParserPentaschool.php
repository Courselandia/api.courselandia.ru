<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Imports\Parsers;

use Util;
use Generator;
use App\Modules\School\Enums\School;
use App\Modules\Course\Entities\ParserCourse;
use App\Modules\Course\Enums\Currency;
use App\Modules\Course\Imports\ParserYml;
use App\Modules\Direction\Enums\Direction;

/**
 * Парсинг курсов Pentaschool
 */
class ParserPentaschool extends ParserYml
{
    /**
     * Вернет школу.
     *
     * @return School Школа.
     */
    public function getSchool(): School
    {
        return School::PENTASCHOOL;
    }

    /**
     * Вернет массив сопоставлений между названиями категорий из источника и их направлениями в системе.
     *
     * @return array<string, Direction> Массив, где ключ это название категории из источника, а значение, это направление в системе.
     */
    public function getDirections(): array
    {
        return [
            'Мода и стиль'  => Direction::OTHER,
            'Графический дизайн'  => Direction::DESIGN,
            'Дизайн интерьера'  => Direction::DESIGN,
            'Ландшафтный дизайн'  => Direction::DESIGN,
            'Веб-дизайн'  => Direction::DESIGN,
            'Motion-дизайн'  => Direction::DESIGN,
            'Творчество'  => Direction::OTHER,
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
            $course->price_old = $offer['oldprice'];
            $course->currency = Currency::RUB;
            $course->image = $offer['picture'] ?? null;
            $course->status = $offer['attributes']['available'] === 'true';

            yield $course;
        }
    }
}
