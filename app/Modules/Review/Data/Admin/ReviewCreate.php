<?php
/**
 * Модуль Отзывов.
 * Этот модуль содержит все классы для работы с отзывами.
 *
 * @package App\Modules\Review
 */

namespace App\Modules\Review\Data\Admin;

use App\Models\Data;
use App\Modules\Review\Enums\Status;
use Carbon\Carbon;

/**
 * Данные для создания отзыва.
 */
class ReviewCreate extends Data
{
    /**
     * ID школы.
     *
     * @var int|null
     */
    public ?int $school_id = null;

    /**
     * ID курса.
     *
     * @var int|null
     */
    public ?int $course_id = null;

    /**
     * Имя автора.
     *
     * @var string|null
     */
    public ?string $name = null;

    /**
     * Заголовок.
     *
     * @var string|null
     */
    public ?string $title = null;

    /**
     * Отзыв.
     *
     * @var string|null
     */
    public ?string $review = null;

    /**
     * Достоинства.
     *
     * @var string|null
     */
    public ?string $advantages = null;

    /**
     * Недостатки.
     *
     * @var string|null
     */
    public ?string $disadvantages = null;

    /**
     * Рейтинг.
     *
     * @var int|null
     */
    public ?int $rating = null;

    /**
     * Статус.
     *
     * @var Status|null
     */
    public ?Status $status = null;

    /**
     * Дата добавления.
     *
     * @var ?Carbon
     */
    public ?Carbon $created_at = null;

    /**
     * Источник.
     *
     * @var ?string
     */
    public ?string $source = null;

    /**
     * @param int|null $school_id ID школы.
     * @param int|null $course_id ID курса.
     * @param string|null $name Имя автора.
     * @param string|null $title Заголовок.
     * @param string|null $review Отзыв.
     * @param string|null $advantages Достоинства.
     * @param string|null $disadvantages Недостатки.
     * @param int|null $rating Рейтинг.
     * @param Status|null $status Статус.
     * @param Carbon|null $created_at Дата добавления.
     * @param string|null $source Источник.
     */
    public function __construct(
        ?int    $school_id = null,
        ?int    $course_id = null,
        ?string $name = null,
        ?string $title = null,
        ?string $review = null,
        ?string $advantages = null,
        ?string $disadvantages = null,
        ?int    $rating = null,
        ?Status $status = null,
        ?Carbon $created_at = null,
        ?string $source = null
    )
    {
        $this->school_id = $school_id;
        $this->course_id = $course_id;
        $this->name = $name;
        $this->title = $title;
        $this->review = $review;
        $this->advantages = $advantages;
        $this->disadvantages = $disadvantages;
        $this->rating = $rating;
        $this->status = $status;
        $this->created_at = $created_at;
        $this->source = $source;
    }
}
