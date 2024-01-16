<?php
/**
 * Модуль Учителей.
 * Этот модуль содержит все классы для работы с учителями.
 *
 * @package App\Modules\Teacher
 */

namespace App\Modules\Teacher\Models;

use Carbon\Carbon;
use Eloquent;
use App\Models\Delete;
use App\Models\Validate;
use App\Models\Sortable;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Modules\Teacher\Database\Factories\TeacherExperienceFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Modules\Teacher\Filters\TeacherExperienceFilter;

/**
 * Класс модель для таблицы опыта работы учителя на основе Eloquent.
 *
 * @property int|string $id ID опыта.
 * @property string|int $teacher_id ID учителя.
 * @property string $place Место работы.
 * @property string $position Должность.
 * @property Carbon|null $started Дата начала работы.
 * @property Carbon|null $finished Дата окончания работы.
 * @property int $weight Вес.
 *
 * @property-read Teacher $teacher
 */
class TeacherExperience extends Eloquent
{
    use Delete;
    use HasFactory;
    use Sortable;
    use SoftDeletes;
    use Validate;
    use Filterable;
    use HasTimestamps;

    /**
     * Атрибуты, для которых разрешено массовое назначение.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'teacher_id',
        'place',
        'position',
        'started',
        'finished',
        'weight',
    ];

    /**
     * Метод, который должен вернуть все правила валидации.
     *
     * @return array Вернет массив правил.
     */
    protected function getRules(): array
    {
        return [
            'teacher_id' => 'required|digits_between:0,20',
            'place' => 'required|between:1,191',
            'position' => 'required|between:1,191',
            'started' => 'date',
            'finished' => 'date',
            'weight' => 'integer|digits_between:0,5',
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
            'teacher_id' => trans('teacher::models.teacherExperience.teacherId'),
            'place' => trans('teacher::models.teacherExperience.place'),
            'position' => trans('teacher::models.teacherExperience.position'),
            'started' => trans('teacher::models.teacherExperience.started'),
            'finished' => trans('teacher::models.teacherExperience.finished'),
            'weight' => trans('teacher::models.teacherExperience.weight'),
        ];
    }

    /**
     * Класс фильтр.
     *
     * @return string Название класса фильтра.
     */
    public function modelFilter(): string
    {
        return $this->provideFilter(TeacherExperienceFilter::class);
    }

    /**
     * Создание новой фабрики для модели.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return TeacherExperienceFactory::new();
    }

    /**
     * Учитель.
     *
     * @return BelongsTo Модель учителя.
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }
}
