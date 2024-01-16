<?php
/**
 * Модуль предупреждений.
 * Этот модуль содержит все классы для работы с предупреждениями.
 *
 * @package App\Modules\Alert
 */

namespace App\Modules\Alert\Models;

use App\Models\Sortable;
use App\Modules\Alert\Filters\AlertFilter;
use Eloquent;
use App\Models\Delete;
use App\Models\Validate;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Status;

/**
 * Класс модель для таблицы предупреждений на основе Eloquent.
 *
 * @property int|string $id ID предупреждения.
 * @property string|null $title Заголовок.
 * @property string|null $description Описание.
 * @property string|null $url URL
 * @property string|null $tag Тэг.
 * @property string|null $color Цвет тэга.
 * @property int $status Статус.
 */
class Alert extends Eloquent
{
    use Delete;
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
        'title',
        'description',
        'url',
        'tag',
        'color',
        'status'
    ];

    /**
     * Метод, который должен вернуть все правила валидации.
     *
     * @return array Вернет массив правил.
     */
    protected function getRules(): array
    {
        return [
            'title' => 'required|between:1,191',
            'description' => 'nullable|max:1000',
            'url' => 'nullable|max:191',
            'tag' => 'nullable|max:50',
            'color' => 'nullable|max:50',
            'status' => 'nullable|boolean'
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
            'pattern' => trans('alert::models.alert.pattern'),
            'description' => trans('alert::models.alert.description'),
            'url' => trans('alert::models.alert.url'),
            'tag' => trans('alert::models.alert.tag'),
            'color' => trans('alert::models.alert.color'),
            'status' => trans('alert::models.alert.status')
        ];
    }

    /**
     * Класс фильтр.
     *
     * @return string Название класса фильтра.
     */
    public function modelFilter(): string
    {
        return $this->provideFilter(AlertFilter::class);
    }
}
