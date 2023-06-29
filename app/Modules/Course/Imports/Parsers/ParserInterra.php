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
 * Парсинг курсов Interra.
 */
class ParserInterra extends ParserYml
{
    /**
     * Вернет школу.
     *
     * @return School Школа.
     */
    public function getSchool(): School
    {
        return School::INTERRA;
    }

    /**
     * Вернет массив сопоставлений между названиями категорий из источника и их направлениями в системе.
     *
     * @return array<string, Direction> Массив, где ключ это название категории из источника, а значение, это направление в системе.
     */
    public function getDirections(): array
    {
        return [
            'Интернет-маркетолог' => Direction::MARKETING,
            'SMM-менеджер' => Direction::MARKETING,
            'Ассистент' => Direction::BUSINESS,
            'Копирайтер-маркетолог' => Direction::MARKETING,
            'Таргетолог' => Direction::MARKETING,
            'Трафик-менеджер' => Direction::MARKETING,
            'Продюсер онлайн курсов' => Direction::MARKETING,
            'Веб-дизайнер' => Direction::DESIGN,
            'Project-менеджер' => Direction::BUSINESS,
            'Куратор онлайн школы' => Direction::BUSINESS,
            'Технический администратор' => Direction::BUSINESS,
            'Методист' => Direction::OTHER,
            'Контент-менеджер' => Direction::MARKETING,
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
            $course->price_old = $offer['oldprice'];
            $course->price_recurrent = $offer['credit_price'];
            $course->currency = Currency::RUB;
            $course->direction = $offer['direction'];

            if (isset($offer['duration'])) {
                $duration = explode(' ', $offer['duration']);
                $course->duration = (int)$duration[0];
                $course->duration_unit = Duration::MONTH;
            }

            yield $course;
        }
    }
}
