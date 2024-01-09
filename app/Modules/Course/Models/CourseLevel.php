<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Models;

use App\Modules\Course\Database\Factories\CourseLevelFactory;
use App\Modules\Salary\Enums\Level;
use App\Models\Enums\EnumList;
use Eloquent;
use App\Models\Delete;
use App\Models\Validate;
use App\Models\Sortable;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Modules\Course\Filters\CourseLevelFilter;

/**
 * Класс модель для таблицы уровней курсов на основе Eloquent.
 *
 * @property int|string $id ID уровня.
 * @property int|string $course_id ID курса.
 * @property string $level Уровень.
 *
 * @property-read Course $course
 */
class CourseLevel extends Eloquent
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
        'level',
    ];

    /**
     * Метод, который должен вернуть все правила валидации.
     *
     * @return array Вернет массив правил.
     */
    protected function getRules(): array
    {
        return [
            'course_id' => 'required|digits_between:0,20|exists_soft:courses,id',
            'level' => 'in:' . implode(',', EnumList::getValues(Level::class)),
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
            'course_id' => trans('course::models.courseLevel.courseId'),
            'level' => trans('course::models.courseLevel.level'),
        ];
    }

    /**
     * Класс фильтр.
     *
     * @return string Название класса фильтра.
     */
    public function modelFilter(): string
    {
        return $this->provideFilter(CourseLevelFilter::class);
    }

    /**
     * Создание новой фабрики для модели.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return CourseLevelFactory::new();
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
