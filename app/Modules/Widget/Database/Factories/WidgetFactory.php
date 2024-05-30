<?php
/**
 * Модуль Виджетов.
 * Этот модуль содержит все классы для работы с виджетами, которые можно использовать в публикациях.
 *
 * @package App\Modules\Widget
 */

namespace App\Modules\Widget\Database\Factories;

use App\Modules\Widget\Models\Widget;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Фабрика модели виджетов.
 */
class WidgetFactory extends Factory
{
    /**
     * Модель фабрики.
     *
     * @var string
     */
    protected $model = Widget::class;

    /**
     * Определение модели.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->text(160),
            'index' => $this->faker->text(160),
            'status' => true,
        ];
    }
}
