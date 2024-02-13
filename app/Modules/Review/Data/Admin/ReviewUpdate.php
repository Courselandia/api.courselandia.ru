<?php
/**
 * Модуль Отзывов.
 * Этот модуль содержит все классы для работы с отзывами.
 *
 * @package App\Modules\Review
 */

namespace App\Modules\Review\Data\Admin;

use App\Modules\Review\Enums\Status;
use Carbon\Carbon;

/**
 * Данные для обновления отзыва.
 */
class ReviewUpdate extends ReviewCreate
{
    /**
     * ID отзыва.
     *
     * @var int|string
     */
    public int|string $id;

    /**
     * @param int|string $id ID отзыва.
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
        int|string $id,
        ?int       $school_id = null,
        ?int       $course_id = null,
        ?string    $name = null,
        ?string    $title = null,
        ?string    $review = null,
        ?string    $advantages = null,
        ?string    $disadvantages = null,
        ?int       $rating = null,
        ?Status    $status = null,
        ?Carbon    $created_at = null,
        ?string    $source = null
    )
    {
        $this->id = $id;

        parent::__construct(
            $school_id,
            $course_id,
            $name,
            $title,
            $review,
            $advantages,
            $disadvantages,
            $rating,
            $status,
            $created_at,
            $source
        );
    }
}
