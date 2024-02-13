<?php
/**
 * Модуль Трудоустройство.
 * Этот модуль содержит все классы для работы с трудоустройствами.
 *
 * @package App\Modules\Employment
 */

namespace App\Modules\Employment\Models;

use Eloquent;
use App\Models\Status;
use App\Models\Delete;
use App\Models\Validate;
use App\Models\Sortable;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Modules\Employment\Database\Factories\EmploymentFactory;
use App\Modules\Employment\Filters\EmploymentFilter;
use App\Modules\Course\Models\Course;

/**
 * Класс модель для таблицы трудоустройства на основе Eloquent.
 *
 * @property int|string $id ID трудоустройства.

 * @property string $name Название.
 * @property string $text Текст.
 * @property string $status Статус.
 *
 * @property-read Course[] $courses
 */
class Employment extends Eloquent
{
    use Delete;
    use HasFactory;
    use Sortable;
    use SoftDeletes;
    use Status;
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
        'name',
        'text',
        'status',
    ];

    /**
     * Метод, который должен вернуть все правила валидации.
     *
     * @return array Вернет массив правил.
     */
    protected function getRules(): array
    {
        return [
            'name' => 'required|between:1,191|unique_soft:employments,name,' . $this->id . ',id',
            'text' => 'max:65000',
            'status' => 'required|boolean'
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
            'name' => trans('employment::models.employment.name'),
            'text' => trans('employment::models.employment.text'),
            'status' => trans('employment::models.employment.status')
        ];
    }

    /**
     * Класс фильтр.
     *
     * @return string Название класса фильтра.
     */
    public function modelFilter(): string
    {
        return $this->provideFilter(EmploymentFilter::class);
    }

    /**
     * Создание новой фабрики для модели.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return EmploymentFactory::new();
    }

    /**
     * Курсы этого трудоустройства.
     *
     * @return BelongsToMany Модели курсов.
     */
    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class);
    }
}
