<?php
/**
 * Модуль Отзывов.
 * Этот модуль содержит все классы для работы с отзывовами.
 *
 * @package App\Modules\Review
 */

namespace App\Modules\Review\Models;

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
 * @property string $name Имя автора.
 * @property string $title Заголовок.
 * @property string $text Текст.
 * @property int $rating Рейтинг.
 * @property string $status Статус.
 *
 * @property-read Review $school
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
        'name',
        'title',
        'text',
        'rating',
        'status',
    ];

    /**
     * Метод, который должен вернуть все правила валидации.
     *
     * @return array Вернет массив правил.
     */
    #[ArrayShape([
        'school_id' => 'string',
        'name' => 'string',
        'title' => 'string',
        'text' => 'string',
        'rating' => 'string',
        'status' => 'string',
    ])] protected function getRules(): array
    {
        return [
            'school_id' => 'required|digits_between:0,20',
            'name' => 'required|between:1,191',
            'title' => 'max:191',
            'text' => 'required|between:1,65000',
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
            'name' => trans('review::models.review.name'),
            'title' => trans('review::models.review.title'),
            'text' => trans('review::models.review.text'),
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
}
