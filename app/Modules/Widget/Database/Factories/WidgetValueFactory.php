<?php
/**
 * Модуль Виджетов.
 * Этот модуль содержит все классы для работы с виджетами, которые можно использовать в публикациях.
 *
 * @package App\Modules\Widget
 */

namespace App\Modules\Widget\Database\Factories;

use App\Modules\Widget\Models\WidgetValue;
use App\Modules\Widget\Models\Widget;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Фабрика модели значений виджетов.
 */
class WidgetValueFactory extends Factory
{
    /**
     * Модель фабрики.
     *
     * @var string
     */
    protected $model = WidgetValue::class;

    /**
     * Определение модели.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'widget_id' => Widget::factory(),
            'name' => $this->faker->text(160),
            'value' => $this->faker->text(160),
        ];
    }
}
