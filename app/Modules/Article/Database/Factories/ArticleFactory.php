<?php
/**
 * Статьи написанные искусственным интеллектом для разных сущностей.
 * Пакет содержит классы для хранения статей написанных искусственным интеллектом.
 *
 * @package App.Models.Article
 */

namespace App\Modules\Article\Database\Factories;

use App\Modules\Article\Enums\Status;
use App\Modules\School\Models\School;
use JetBrains\PhpStorm\ArrayShape;
use App\Modules\Article\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Фабрика модели написанных текстов.
 */
class ArticleFactory extends Factory
{
    /**
     * Модель фабрики.
     *
     * @var string
     */
    protected $model = Article::class;

    /**
     * Определение модели.
     *
     * @return array
     */
    #[ArrayShape([
        'task_id' => 'integer',
        'category' => 'string',
        'request' => 'string',
        'text' => 'string',
        'params' => 'string',
        'status' => 'string',
        'articleable_id' => 'integer',
        'articleable_type' => 'string',
    ])] public function definition(): array
    {
        $school = School::factory()->create();

        return [
            'task_id' => $this->faker->numberBetween(),
            'category' => $this->faker->text(160),
            'request' => $this->faker->text(1000),
            'text' => $this->faker->text(5000),
            'params' => json_encode([]),
            'status' => Status::READY->value,
            'articleable_id' => $school->id,
            'articleable_type' => School::class,
        ];
    }
}
