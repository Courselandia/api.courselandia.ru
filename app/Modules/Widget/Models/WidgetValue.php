<?php
/**
 * Модуль Виджетов.
 * Этот модуль содержит все классы для работы с виджетами, которые можно использовать в публикациях.
 *
 * @package App\Modules\Widget
 */

namespace App\Modules\Widget\Models;

use Eloquent;
use App\Models\Delete;
use App\Models\Validate;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Modules\Widget\Database\Factories\WidgetFactory;

/**
 * Класс модель для таблицы значений виджетов на основе Eloquent.
 *
 * @property int|string $id ID значения виджета.
 * @property int|string $widget_id ID виджета.
 * @property string $name Название.
 * @property string $value Значение.
 *
 * @property-read Widget $widget
 */
class WidgetValue extends Eloquent
{
    use Delete;
    use HasFactory;
    use SoftDeletes;
    use Validate;
    use HasTimestamps;

    /**
     * Атрибуты, для которых разрешено массовое назначение.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'widget_id',
        'name',
        'value',
    ];

    protected $casts = [
        'value' => 'array',
    ];

    /**
     * Метод, который должен вернуть все правила валидации.
     *
     * @return array Вернет массив правил.
     */
    protected function getRules(): array
    {
        return [
            'widget_id' => 'required|digits_between:0,20',
            'name' => 'required|between:1,191',
            'value' => 'required|json',
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
            'widget_id' => trans('widget::models.widgetValue.widgetId'),
            'name' => trans('widget::models.widget.name'),
            'value' => trans('widget::models.widget.value'),
        ];
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
     * Получить виджета.
     *
     * @return BelongsTo Вернет модель виджета.
     */
    public function widget(): BelongsTo
    {
        return $this->belongsTo(WidgetValue::class);
    }
}
