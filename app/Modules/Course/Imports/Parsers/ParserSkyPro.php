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
 * Парсинг курсов SkyPro.
 */
class ParserSkyPro extends ParserYml
{
    /**
     * Вернет школу.
     *
     * @return School Школа.
     */
    public function getSchool(): School
    {
        return School::SKYPRO;
    }

    /**
     * Вернет массив сопоставлений между названиями категорий из источника и их направлениями в системе.
     *
     * @return array<string, Direction> Массив, где ключ это название категории из источника, а значение, это направление в системе.
     */
    public function getDirections(): array
    {
        return [
            'Программирование' => Direction::PROGRAMMING,
            'Аналитика' => Direction::ANALYTICS,
            'Разное' => Direction::OTHER,
            'Маркетинг' => Direction::MARKETING,
            'Интернет-маркетинг' => Direction::MARKETING,
            'Таргетированная реклама' => Direction::MARKETING,
            'Веб-дизайн' => Direction::DESIGN,
            'Экономика, финансы, бухгалтерия' => Direction::BUSINESS,
            'Веб-разработка' => Direction::PROGRAMMING,
            'Разработка на Python' => Direction::PROGRAMMING,
            'Разработка на Java' => Direction::PROGRAMMING,
            'Аналитика данных' => Direction::ANALYTICS,
            'Тестирование' => Direction::PROGRAMMING,
            'Графический дизайнер' => Direction::DESIGN,
            'Excel и Google Spreadsheets' => Direction::OTHER,
            'Анализ данных' => Direction::ANALYTICS,
            'Финансовая аналитика' => Direction::BUSINESS,
            'Бизнес аналитика' => Direction::ANALYTICS,
            'Продуктовая аналитика' => Direction::ANALYTICS,
            'BI аналитика' => Direction::ANALYTICS,
            'Программирование для анализа данных' => Direction::PROGRAMMING,
            'HTML и CSS- разработка' => Direction::PROGRAMMING,
            'Frontend-разработка' => Direction::PROGRAMMING,
            'Тестирование ПО' => Direction::PROGRAMMING,
            'Backend-разработка' => Direction::PROGRAMMING,
            'SQL' => Direction::PROGRAMMING,
            'Бизнес-аналитика' => Direction::ANALYTICS,
            'BI-аналитика' => Direction::ANALYTICS,
            'QA-тестирование' => Direction::PROGRAMMING,
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
            $course->direction = $offer['direction'];
            $course->price = $offer['price'];
            $course->currency = Currency::RUB;

            $priceRecurrent = $offer['params']['Ежемесячная цена']['value'] ?? null;
            $priceRecurrent = is_numeric($priceRecurrent) ? $priceRecurrent : null;

            $course->price_recurrent = $priceRecurrent;
            $course->duration = $offer['params']['Продолжительность']['value'] ?? null;

            if (isset($offer['params']['Продолжительность']['unit']) || isset($offer['params']['Продолжительность']['value'])) {
                $course->duration_unit = isset($offer['params']['Продолжительность']['unit'])
                    ? $this->getDurationUnit($offer['params']['Продолжительность']['unit'])
                    : Duration::MONTH;
            }

            $course->text = $offer['description'];
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
