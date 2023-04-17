<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Review\Imports;

use Util;
use Generator;
use App\Models\Error;
use App\Modules\Review\Entities\ParserReview;
use App\Modules\Review\Models\Review;
use App\Modules\School\Enums\School;

/**
 * Абстрактный класс для создания собственного парсера.
 */
abstract class Parser
{
    use Error;

    /**
     * Школа.
     *
     * @var School
     */
    private School $school;

    /**
     * Путь к источнику отзывов для парсинга.
     *
     * @var string
     */
    private string $url;

    /**
     * Чтение отзывов.
     *
     * @return Generator<ParserReview>
     */
    abstract public function read(): Generator;

    /**
     * Конструктор.
     *
     * @param School $school Школа отзывы который мы парсим.
     * @param string $url Путь к источнику отзывов для парсена.
     */
    public function __construct(School $school, string $url)
    {
        $this->school = $school;
        $this->url = $url;
    }

    /**
     * Вернет источник.
     *
     * @return string
     */
    public function getSource(): string
    {
        return parse_url($this->getUrl())['host'];
    }

    /**
     * Вернет школу.
     *
     * @return School Школа.
     */
    public function getSchool(): School
    {
        return $this->school;
    }

    /**
     * Вернет URL для парсинга.
     *
     * @return string URL.
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * Уникальный ключ для проверки уникальности отзыва.
     *
     * @param ParserReview $review Спарсенный отзыв.
     *
     * @return string Ключ.
     */
    public function getUuid(ParserReview $review): string
    {
        return Util::getKey([
            $this->getSchool()->value,
            $review->name,
            $review->title,
            $review->review,
            $review->advantages,
            $review->disadvantages,
            $review->rating,
            $review->date->format('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Проверка существует ли уже этот отзыв или нет.
     *
     * @param ParserReview $review Спарсенный отзыв.
     *
     * @return bool Вернет признак наличия отзыва в базе данных.
     */
    public function isReviewExist(ParserReview $review): bool
    {
        return Review::where('uuid',  $this->getUuid($review))->exists();
    }
}
