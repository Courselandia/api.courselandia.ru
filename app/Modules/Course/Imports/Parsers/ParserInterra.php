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
            'Работа с командой в проекте' => Direction::BUSINESS,
            'Создаем контент для социальных сетей' => Direction::MARKETING,
            'Как создавать контент для презентаций' => Direction::MARKETING,
            'Основы работы с текстом' => Direction::MARKETING,
            'Как создать TikTok аккаунт и заработать на нем' => Direction::MARKETING,
            'Как создать Яндекс.Дзен канал и заработать на нем' => Direction::MARKETING,
            'Основа работы в Photoshop' => Direction::DESIGN,
            'Создание чек-листов, инструкций, мини-книг и пр.' => Direction::OTHER,
            'Influence-маркетинг. Покупка рекламы у блогеров' => Direction::MARKETING,
            'Интеллектуальная собственность' => Direction::OTHER,
            'Как оформить резюме' => Direction::OTHER,
            'Написание SEO-статей для сайтов' => Direction::MARKETING,
            'Создание Landing Page в Тilda' => Direction::PROGRAMMING,
            'Как трудоустроиться в интернете' => Direction::OTHER,
            'Как найти и проконтролировать подрядчиков' => Direction::BUSINESS,
            'Как упаковать тренинг на платформе Getcourse' => Direction::OTHER,
            'Стратегии монетизации сообществ в социальных сетях' => Direction::MARKETING,
            'Работа на биржах фриланса' => Direction::OTHER,
            'Медийная реклама в Яндексе' => Direction::MARKETING,
            'Чат-боты и рассылки в мессенджерах' => Direction::MARKETING,
            'Создание сайта в Figma' => Direction::DESIGN,
            'Сторителлинг. Искусство создания цепляющих историй' => Direction::OTHER,
            'Формирование отдела продаж'  => Direction::BUSINESS,
            'Таргетированная реклама в Facebook и Instagram' => Direction::MARKETING,
            'Быстрый старт в Telegram' => Direction::MARKETING,
            'Профессия Telegram-маркетолог' => Direction::MARKETING,
            'Профессия Influence-маркетолог' => Direction::MARKETING,
            'Telegram-маркетолог' => Direction::MARKETING,
            'Influence-маркетолог' => Direction::MARKETING,
            'Маркетинг' => Direction::MARKETING,
            'Менеджмент' => Direction::BUSINESS,
            'Дизайн' => Direction::DESIGN,
            'Образование' => Direction::OTHER,
            'IT' => Direction::OTHER,
            'Продажи' => Direction::MARKETING,
            'Трудоустройство' => Direction::MARKETING,
            'Юридические моменты' => Direction::OTHER,
            'Копирайтинг' => Direction::OTHER,
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
                $course->duration = (int)$offer['duration']['value'];
                $course->duration_unit = $this->getDurationUnit($offer['duration']['attributes']['unit']);
            }

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
        if ($duration === 'year') {
            return Duration::YEAR;
        } elseif ($duration === 'month') {
            return Duration::MONTH;
        } elseif ($duration === 'week') {
            return Duration::WEEK;
        } elseif ($duration === 'day') {
            return Duration::DAY;
        }

        return null;
    }
}
