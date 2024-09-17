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
 * Парсинг курсов GeekBrains.
 */
class ParserGeekBrains extends ParserYml
{
    /**
     * Вернет школу.
     *
     * @return School Школа.
     */
    public function getSchool(): School
    {
        return School::GEEKBRAINS;
    }

    /**
     * Вернет массив сопоставлений между названиями категорий из источника и их направлениями в системе.
     *
     * @return array<string, Direction> Массив, где ключ это название категории из источника, а значение, это направление в системе.
     */
    public function getDirections(): array
    {
        return [
            'Разработка игр'  => Direction::GAMES,
            'Программирование' => Direction::PROGRAMMING,
            'Дизайн' => Direction::DESIGN,
            'Маркетинг' => Direction::MARKETING,
            'Школа креативных профессий' => Direction::OTHER,
            'Бизнес-образование' => Direction::BUSINESS,
            'Другое' => Direction::OTHER,
            'Аналитика' => Direction::ANALYTICS,
            'ИТ-инфрастуктура' => Direction::PROGRAMMING,
            'Разработка' => Direction::PROGRAMMING,
            'Аналитика и Data Science' => Direction::ANALYTICS,
            'Менеджмент' => Direction::BUSINESS,
            'GeekSchool' => Direction::PROGRAMMING,
            'translation missing: ru.product_feed.categories.design_old' => Direction::DESIGN,
            'translation missing: ru.product_feed.categories.design_new' => Direction::DESIGN,
            'translation missing: ru.product_feed.categories.it_old' => Direction::PROGRAMMING,
            'translation missing: ru.product_feed.categories.it_new' => Direction::PROGRAMMING,
            'translation missing: ru.product_feed.categories.marketing_old' => Direction::MARKETING,
            'translation missing: ru.product_feed.categories.marketing_new' => Direction::MARKETING,
            'translation missing: ru.product_feed.categories.management_old' => Direction::BUSINESS,
            'translation missing: ru.product_feed.categories.management_new' => Direction::BUSINESS,
            'Игры' => Direction::GAMES,
            'Управление' => Direction::BUSINESS,
            'Инженерия' => Direction::PROGRAMMING,
            'Кино и Музыка' => Direction::OTHER,
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
            $course->uuid = $offer['attributes']['id'];
            $course->school = $this->getSchool();
            $course->url = $offer['url'];
            $course->price = $offer['price'];
            $course->image = $offer['picture'] ?? null;
            $course->name = $offer['name'];
            $course->text = $offer['description'];
            $course->status = true;
            $course->currency = Currency::RUB;
            $course->direction = $offer['direction'];

            if (isset($offer['oldprice']) && $offer['oldprice']) {
                $course->price_old = $offer['oldprice'];
            }

            if (isset($offer['params']['Продолжительность обучения, месяцев']['value']) && $offer['params']['Продолжительность обучения, месяцев']['value']) {
                $course->duration = $offer['params']['Продолжительность обучения, месяцев']['value'];
                $course->duration_unit = Duration::MONTH;
            }

            yield $course;
        }
    }
}
