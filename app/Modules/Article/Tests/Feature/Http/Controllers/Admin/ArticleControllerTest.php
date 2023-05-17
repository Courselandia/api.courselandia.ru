<?php
/**
 * Статьи написанные искусственным интеллектом для разных сущностей.
 * Пакет содержит классы для хранения статей написанных искусственным интеллектом.
 *
 * @package App.Models.Article
 */

namespace App\Modules\Article\Tests\Feature\Http\Controllers\Admin;

use App\Models\Test\TokenTest;
use App\Modules\Article\Models\Article;
use Faker\Factory as Faker;
use JetBrains\PhpStorm\Pure;
use Tests\TestCase;

/**
 * Тестирование: Класс контроллер для статей.
 */
class ArticleControllerTest extends TestCase
{
    use TokenTest;

    /**
     * Чтение данных.
     *
     * @return void
     */
    public function testRead(): void
    {
        $article = Article::factory()->create();

        $this->json(
            'GET',
            'api/private/admin/article/read',
            [
                'offset' => 0,
                'limit' => 10,
                'sorts' => [
                    'id' => 'DESC',
                ],
                'filters' => [
                    'category' => $article->category,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'data' => [
                '*' => $this->getArticleStructure()
            ],
            'total',
            'success',
        ]);
    }

    /**
     * Получение записи.
     *
     * @return void
     */
    public function testGet(): void
    {
        $article = Article::factory()->create();

        $this->json(
            'GET',
            'api/private/admin/article/get/' . $article->id,
            [],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'data' => $this->getArticleStructure(),
            'success',
        ]);
    }

    /**
     * Получение записи с ошибкой при отсутствии записи.
     *
     * @return void
     */
    public function testGetNotExist(): void
    {
        $this->json(
            'GET',
            'api/private/admin/article/get/1000',
            [],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(404)->assertJsonStructure([
            'data',
            'success',
        ]);
    }

    /**
     * Обновление данных.
     *
     * @return void
     */
    public function testUpdate(): void
    {
        $article = Article::factory()->create();
        $faker = Faker::create();

        $this->json(
            'PUT',
            'api/private/admin/article/update/' . $article->id,
            [
                'text' => $faker->text(10000),
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => $this->getArticleStructure(),
        ]);
    }

    /**
     * Обновление данных с ошибкой.
     *
     * @return void
     */
    public function testUpdateNotValid(): void
    {
        $article = Article::factory()->create();
        $faker = Faker::create();

        $this->json(
            'PUT',
            'api/private/admin/article/update/' . $article->id,
            [
                'description' => $faker->text(10000),
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(400)->assertJsonStructure([
            'success',
            'message',
        ]);
    }

    /**
     * Обновление данных с ошибкой для несуществующей записи.
     *
     * @return void
     */
    public function testUpdateNotExist(): void
    {
        $faker = Faker::create();

        $this->json(
            'PUT',
            'api/private/admin/article/update/1000',
            [
                'text' => $faker->text(10000),
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(404)->assertJsonStructure([
            'success',
            'message',
        ]);
    }

    /**
     * Обновление статуса.
     *
     * @return void
     */
    public function testUpdateStatus(): void
    {
        $article = Article::factory()->create();

        $this->json(
            'PUT',
            'api/private/admin/article/update/status/' . $article->id,
            [
                'status' => 'failed',
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => $this->getArticleStructure(),
        ]);
    }

    /**
     * Обновление статуса с ошибкой.
     *
     * @return void
     */
    public function testUpdateStatusNotValid(): void
    {
        $article = Article::factory()->create();

        $this->json(
            'PUT',
            'api/private/admin/article/update/status/' . $article->id,
            [
                'status' => 'test',
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(400)->assertJsonStructure([
            'success',
            'message',
        ]);
    }

    /**
     * Обновление статуса с ошибкой для несуществующей записи.
     *
     * @return void
     */
    public function testUpdateStatusNotExist(): void
    {
        $this->json(
            'PUT',
            'api/private/admin/article/update/status/1000',
            [
                'status' => 'failed',
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(404)->assertJsonStructure([
            'success',
            'message',
        ]);
    }

    /**
     * Переписание текста.
     *
     * @return void
     */
    public function testRewrite(): void
    {
        $article = Article::factory()->create();
        $faker = Faker::create();

        $this->json(
            'PUT',
            'api/private/admin/article/rewrite/' . $article->id,
            [
                'request' => $faker->text(),
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => $this->getArticleStructure(),
        ]);
    }

    /**
     * Переписание текста с ошибкой.
     *
     * @return void
     */
    public function testRewriteNotValid(): void
    {
        $article = Article::factory()->create();

        $this->json(
            'PUT',
            'api/private/admin/article/rewrite/' . $article->id, [],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(400)->assertJsonStructure([
            'success',
            'message',
        ]);
    }

    /**
     * Переписание текста с ошибкой для несуществующей записи.
     *
     * @return void
     */
    public function testRewriteNotExist(): void
    {
        $faker = Faker::create();

        $this->json(
            'PUT',
            'api/private/admin/article/rewrite/1000',
            [
                'request' => $faker->text(),
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(404)->assertJsonStructure([
            'success',
            'message',
        ]);
    }

    /**
     * Принятие и перенос текста.
     *
     * @return void
     */
    public function testApply(): void
    {
        $article = Article::factory()->create();
        $faker = Faker::create();

        $this->json(
            'PUT',
            'api/private/admin/article/apply/' . $article->id, [],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => $this->getArticleStructure(),
        ]);
    }

    /**
     * Переписание текста с ошибкой для несуществующей записи.
     *
     * @return void
     */
    public function testApplyNotExist(): void
    {
        $this->json(
            'PUT',
            'api/private/admin/article/apply/1000', [],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(404)->assertJsonStructure([
            'success',
            'message',
        ]);
    }

    /**
     * Получить структуру данных статьи.
     *
     * @return array Массив структуры данных статьи.
     */
    #[Pure] private function getArticleStructure(): array
    {
        return [
            'id',
            'task_id',
            'category',
            'category_name',
            'request',
            'text',
            'text_current',
            'params',
            'tries',
            'status',
            'articleable_id',
            'articleable_type',
            'created_at',
            'updated_at',
            'deleted_at',
            'articleable',
        ];
    }
}
