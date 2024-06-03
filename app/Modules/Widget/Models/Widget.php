<?php
/**
 * Модуль Виджетов.
 * Этот модуль содержит все классы для работы с виджетами, которые можно использовать в публикациях.
 *
 * @package App\Modules\Widget
 */

namespace App\Modules\Widget\Models;

use Eloquent;
use App\Models\Status;
use App\Models\Delete;
use App\Models\Validate;
use App\Models\Sortable;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Modules\Widget\Database\Factories\WidgetFactory;
use App\Modules\Widget\Filters\WidgetFilter;

/**
 * Класс модель для таблицы виджетов на основе Eloquent.
 *
 * @property int|string $id ID виджета.
 * @property string $name Название.
 * @property string $index Индекс.
 * @property string $status Статус.
 *
 * @property-read WidgetValue[] $values
 */
class Widget extends Eloquent
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
        'index',
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
            'name' => 'required|between:1,191',
            'index' => 'required|between:1,191|alpha_dash|unique_soft:widgets,index,' . $this->id . ',id',
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
            'name' => trans('widget::models.widget.name'),
            'index' => trans('widget::models.widget.index'),
            'status' => trans('widget::models.widget.status')
        ];
    }

    /**
     * Класс фильтр.
     *
     * @return string Название класса фильтра.
     */
    public function modelFilter(): string
    {
        return $this->provideFilter(WidgetFilter::class);
    }

    /**
     * Создание новой фабрики для модели.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return WidgetFactory::new();
    }

    /**
     * Получить значения виджетов.
     *
     * @return HasMany Вернет модели значений виджетов.
     */
    public function values(): HasMany
    {
        return $this->hasMany(WidgetValue::class);
    }
}
