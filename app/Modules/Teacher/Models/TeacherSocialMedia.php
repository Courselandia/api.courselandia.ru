<?php
/**
 * Модуль Учителей.
 * Этот модуль содержит все классы для работы с учителями.
 *
 * @package App\Modules\Teacher
 */

namespace App\Modules\Teacher\Models;

use Eloquent;
use App\Models\Delete;
use App\Models\Validate;
use App\Models\Sortable;
use EloquentFilter\Filterable;
use JetBrains\PhpStorm\ArrayShape;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Modules\Teacher\Database\Factories\TeacherSocialMediaFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Modules\Teacher\Filters\TeacherSocialMediaFilter;

/**
 * Класс модель для таблицы социальных сетей учителя на основе Eloquent.
 *
 * @property int|string $id ID опыта.
 * @property string|int $teacher_id ID учителя.
 * @property string $name Название.
 * @property string $value Значение.
 *
 * @property-read Teacher $teacher
 */
class TeacherSocialMedia extends Eloquent
{
    use Delete;
    use HasFactory;
    use Sortable;
    use SoftDeletes;
    use Validate;
    use Filterable;

    public function getTable(): string
    {
        return 'teacher_social_medias';
    }

    /**
     * Атрибуты, для которых разрешено массовое назначение.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'teacher_id',
        'name',
        'value',
    ];

    /**
     * Метод, который должен вернуть все правила валидации.
     *
     * @return array Вернет массив правил.
     */
    #[ArrayShape([
        'id' => 'string',
        'teacher_id' => 'string',
        'name' => 'string',
        'value' => 'string',
    ])] protected function getRules(): array
    {
        return [
            'teacher_id' => 'required|digits_between:0,20',
            'name' => 'required|between:1,191',
            'value' => 'required|between:1,191',
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
            'teacher_id' => trans('teacher::models.teacherSocialMedia.teacherId'),
            'name' => trans('teacher::models.teacherSocialMedia.name'),
            'value' => trans('teacher::models.teacherSocialMedia.value'),
        ];
    }

    /**
     * Класс фильтр.
     *
     * @return string Название класса фильтра.
     */
    public function modelFilter(): string
    {
        return $this->provideFilter(TeacherSocialMediaFilter::class);
    }

    /**
     * Создание новой фабрики для модели.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return TeacherSocialMediaFactory::new();
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
