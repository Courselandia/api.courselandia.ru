<?php
/**
 * Статьи написанные искусственным интеллектом для разных сущностей.
 * Пакет содержит классы для хранения статей написанных искусственным интеллектом.
 *
 * @package App.Models.Article
 */

namespace App\Modules\Article\Database\Factories;

use App\Modules\Article\Enums\Status;
use App\Modules\Course\Models\Course;
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
        $course = Course::factory()->create();

        return [
            'task_id' => $this->faker->numberBetween(),
            'category' => 'course.text',
            'request' => $this->faker->text(1000),
            'text' => $this->faker->text(4000),
            'params' => null,
            'status' => Status::READY->value,
            'articleable_id' => $course->id,
            'articleable_type' => Course::class,
        ];
    }
}
