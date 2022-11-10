<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Models;

use App\Modules\Course\Database\Factories\CourseFeatureFactory;
use App\Modules\Course\Filters\CourseFeatureFilter;
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

/**
 * Класс модель для таблицы особенности курса на основе Eloquent.
 *
 * @property int|string $id ID уровня.
 * @property int|string $course_id ID курса.
 * @property string $icon Иконка.
 * @property string $text Описание.
 *
 * @property-read Course $course
 */
class CourseFeature extends Eloquent
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
        'course_id',
        'icon',
        'text',
    ];

    /**
     * Метод, который должен вернуть все правила валидации.
     *
     * @return array Вернет массив правил.
     */
    #[ArrayShape([
        'course_id' => 'string',
        'icon' => 'string',
        'text' => 'string',
    ])] protected function getRules(): array
    {
        return [
            'course_id' => 'required|digits_between:0,20|exists_soft:courses,id',
            'icon' => 'required|between:1,30',
            'text' => 'required|between:1,191',
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
            'course_id' => trans('course::models.courseFeature.courseId'),
            'icon' => trans('course::models.courseFeature.icon'),
            'text' => trans('course::models.courseFeature.text'),
        ];
    }

    /**
     * Класс фильтр.
     *
     * @return string Название класса фильтра.
     */
    public function modelFilter(): string
    {
        return $this->provideFilter(CourseFeatureFilter::class);
    }

    /**
     * Создание новой фабрики для модели.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return CourseFeatureFactory::new();
    }

    /**
     * Получить курс.
     *
     * @return BelongsTo Модель курса.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
