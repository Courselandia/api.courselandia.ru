<?php
/**
 * Модуль Отзывов.
 * Этот модуль содержит все классы для работы с отзывовами.
 *
 * @package App\Modules\Review
 */

namespace App\Modules\Review\Models;

use App\Modules\Course\Models\Course;
use App\Modules\School\Models\School;
use Eloquent;
use App\Models\Delete;
use App\Models\Validate;
use App\Models\Sortable;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use JetBrains\PhpStorm\ArrayShape;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Modules\Review\Database\Factories\ReviewFactory;
use App\Modules\Review\Filters\ReviewFilter;
use App\Models\Enums\EnumList;
use App\Modules\Review\Enums\Status;

/**
 * Класс модель для таблицы отзывов на основе Eloquent.
 *
 * @property int|string $id ID школы.
 * @property int|string $school_id ID школы.
 * @property int|string $course_id ID курса.
 * @property string $uuid Уникальный идентификатор спарсенного отзыва.
 * @property string $source Источник отзыва.
 * @property string $name Имя автора.
 * @property string $title Заголовок.
 * @property string $review Отзыв.
 * @property string $advantages Достоинства.
 * @property string $disadvantages Недостатки.
 * @property int $rating Рейтинг.
 * @property string $status Статус.
 *
 * @property-read School $school
 */
class Review extends Eloquent
{
    use Delete;
    use HasFactory;
    use Sortable;
    use SoftDeletes;
    use Validate;
    use Filterable;

    /**
     * Атрибуты, для которых разрешено массовое назначение.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'school_id',
        'course_id',
        'source',
        'uuid',
        'name',
        'title',
        'review',
        'advantages',
        'disadvantages',
        'rating',
        'status',
        'created_at',
    ];

    /**
     * Метод, который должен вернуть все правила валидации.
     *
     * @return array Вернет массив правил.
     */
    #[ArrayShape([
        'school_id' => 'string',
        'course_id' => 'string',
        'source' => 'string',
        'uuid' => 'string',
        'name' => 'string',
        'title' => 'string',
        'review' => 'string',
        'advantages' => 'string',
        'disadvantages' => 'string',
        'rating' => 'string',
        'status' => 'string',
    ])] protected function getRules(): array
    {
        return [
            'school_id' => 'required|digits_between:0,20|exists_soft:schools,id',
            'course_id' => 'nullable|digits_between:0,20|exists_soft:courses,id',
            'source' => 'max:191',
            'uuid' => 'max:191',
            'name' => 'max:191',
            'title' => 'max:191',
            'review' => 'max:65000',
            'advantages' => 'max:65000',
            'disadvantages' => 'max:65000',
            'rating' => 'integer|between:0,5',
            'status' => 'required|in:' . implode(',', EnumList::getValues(Status::class)),
        ];
    }

    /**
     * Метод, который должен вернуть все названия атрибутов.
     *
     * @return array Массив возможных ошибок валидации.
     */
    protected function getNames(): array
    {
        return [
            'school_id' => trans('review::models.review.schoolId'),
            'course_id' => trans('review::models.review.courseId'),
            'source' => trans('review::models.review.source'),
            'uuid' => trans('review::models.review.uuid'),
            'name' => trans('review::models.review.name'),
            'title' => trans('review::models.review.title'),
            'review' => trans('review::models.review.review'),
            'advantages' => trans('review::models.review.advantages'),
            'disadvantages' => trans('review::models.review.disadvantages'),
            'rating' => trans('review::models.review.rating'),
            'status' => trans('review::models.review.status')
        ];
    }

    /**
     * Класс фильтр.
     *
     * @return string Название класса фильтра.
     */
    public function modelFilter(): string
    {
        return $this->provideFilter(ReviewFilter::class);
    }

    /**
     * Создание новой фабрики для модели.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return ReviewFactory::new();
    }

    /**
     * Получить школу.
     *
     * @return BelongsTo Модель школы.
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Получить курс.
     *
     * @return BelongsTo Модель курсов.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
