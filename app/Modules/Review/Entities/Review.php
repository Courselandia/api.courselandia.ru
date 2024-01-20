<?php
/**
 * Модуль Отзывов.
 * Этот модуль содержит все классы для работы с отзывами.
 *
 * @package App\Modules\Review
 */

namespace App\Modules\Review\Entities;

use App\Models\EntityNew;
use App\Modules\Course\Entities\Course;
use App\Modules\Review\Enums\Status;
use App\Modules\School\Entities\School;
use Carbon\Carbon;

/**
 * Сущность для отзывов.
 */
class Review extends EntityNew
{
    /**
     * ID записи.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * ID школы.
     *
     * @var int|string|null
     */
    public int|string|null $school_id = null;

    /**
     * ID курса.
     *
     * @var int|string|null
     */
    public int|string|null $course_id = null;

    /**
     * Источник.
     *
     * @var string|null
     */
    public ?string $source = null;

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
     * Школа.
     *
     * @var School|null
     */
    public ?School $school = null;

    /**
     * Курс.
     *
     * @var Course|null
     */
    public ?Course $course = null;

    /**
     * Дата создания.
     *
     * @var ?Carbon
     */
    public ?Carbon $created_at = null;

    /**
     * Дата обновления.
     *
     * @var ?Carbon
     */
    public ?Carbon $updated_at = null;

    /**
     * Дата удаления.
     *
     * @var ?Carbon
     */
    public ?Carbon $deleted_at = null;

    /**
     * @param int|string|null $id ID записи.
     * @param int|string|null $school_id ID школы.
     * @param int|string|null $course_id ID курса.
     * @param string|null $source Источник.
     * @param string|null $name Имя автора.
     * @param string|null $title Заголовок.
     * @param string|null $review Отзыв.
     * @param string|null $advantages Достоинства.
     * @param string|null $disadvantages Недостатки.
     * @param int|null $rating Рейтинг.
     * @param Status|null $status Статус.
     * @param Carbon|null $created_at Дата создания.
     * @param Carbon|null $updated_at Дата обновления.
     * @param Carbon|null $deleted_at Дата удаления.
     * @param School|null $school Школа.
     * @param Course|null $course Курс.
     */
    public function __construct(
        int|string|null $id = null,
        int|string|null $school_id = null,
        int|string|null $course_id = null,
        ?string         $source = null,
        ?string         $name = null,
        ?string         $title = null,
        ?string         $review = null,
        ?string         $advantages = null,
        ?string         $disadvantages = null,
        ?int            $rating = null,
        ?Status         $status = null,
        ?Carbon         $created_at = null,
        ?Carbon         $updated_at = null,
        ?Carbon         $deleted_at = null,
        ?School         $school = null,
        ?Course         $course = null,
    )
    {
        $this->id = $id;
        $this->school_id = $school_id;
        $this->course_id = $course_id;
        $this->source = $source;
        $this->name = $name;
        $this->title = $title;
        $this->review = $review;
        $this->advantages = $advantages;
        $this->disadvantages = $disadvantages;
        $this->rating = $rating;
        $this->status = $status;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
        $this->deleted_at = $deleted_at;
        $this->school = $school;
        $this->course = $course;
    }
}
