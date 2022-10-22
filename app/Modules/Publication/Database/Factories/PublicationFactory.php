<?php
/**
 * Модуль Публикации.
 * Этот модуль содержит все классы для работы с публикациями.
 *
 * @package App\Modules\Publication
 */

namespace App\Modules\Publication\Database\Factories;

use App\Modules\Metatag\Models\Metatag;
use Util;
use Carbon\Carbon;
use JetBrains\PhpStorm\ArrayShape;
use App\Modules\Publication\Models\Publication;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Фабрика модели публикаций.
 */
class PublicationFactory extends Factory
{
    /**
     * Модель фабрики.
     *
     * @var string
     */
    protected $model = Publication::class;

    /**
     * Определение модели.
     *
     * @return array
     */
    #[ArrayShape([
        'metatag_id' => '\Illuminate\Database\Eloquent\Factories\Factory',
        'published_at' => '\Carbon\Carbon',
        'header' => 'string',
        'link' => 'string',
        'anons' => 'string',
        'article' => 'string',
        'status' => 'bool'
    ])] public function definition(): array
    {
        return [
            'metatag_id' => Metatag::factory(),
            'published_at' => Carbon::now(),
            'header' => $this->faker->title,
            'link' => Util::latin($this->faker->name),
            'anons' => $this->faker->text(1000),
            'article' => $this->faker->text(5000),
            'status' => true,
        ];
    }
}
