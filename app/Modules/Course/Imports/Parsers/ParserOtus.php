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
 * Парсинг курсов Otus.
 */
class ParserOtus extends ParserYml
{
    /**
     * Вернет школу.
     *
     * @return School Школа.
     */
    public function getSchool(): School
    {
        return School::OTUS;
    }

    /**
     * Вернет массив сопоставлений между названиями категорий из источника и их направлениями в системе.
     *
     * @return array<string, Direction> Массив, где ключ это название категории из источника, а значение, это направление в системе.
     */
    public function getDirections(): array
    {
        return [
            'Аналитика' => Direction::ANALYTICS,
            'Программирование' => Direction::PROGRAMMING,
            'Инфраструктура' => Direction::OTHER,
            'Data Science' => Direction::PROGRAMMING,
            'GameDev' => Direction::GAMES,
            'Управление' => Direction::BUSINESS,
            'Тестирование' => Direction::OTHER,
            'Корпоративные курсы' => Direction::OTHER,
            'Архитектура' => Direction::OTHER,
            'Безопасность' => Direction::OTHER,
            'Аналитика и анализ' => Direction::ANALYTICS,
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
            $course->text = $offer['description'] === 'None' ? '' : $offer['description'];
            $course->status = $offer['attributes']['available'] === 'true';
            $course->url = $offer['url'];
            $course->image = $offer['picture'] ?? null;
            $course->price = $offer['price'];
            $course->currency = Currency::RUB;
            $course->direction = $offer['direction'];

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
