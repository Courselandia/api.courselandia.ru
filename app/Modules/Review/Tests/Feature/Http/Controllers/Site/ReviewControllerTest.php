<?php
/**
 * Модуль Отзывов.
 * Этот модуль содержит все классы для работы с отзывами.
 *
 * @package App\Modules\Review
 */

namespace App\Modules\Review\Tests\Feature\Http\Controllers\Site;

use App\Modules\Review\Models\Review;
use Tests\TestCase;

/**
 * Тестирование: Класс контроллер для отзывов.
 */
class ReviewControllerTest extends TestCase
{
    /**
     * Чтение данных.
     *
     * @return void
     */
    public function testRead(): void
    {
        $review = Review::factory()->create();

        $this->json(
            'GET',
            'api/private/site/review/read',
            [
                'offset' => 0,
                'limit' => 10,
                'school_id' => $review->school_id
            ],
        )->assertStatus(200)->assertJsonStructure([
            'data' => [
                '*' => $this->getReviewStructure()
            ],
            'total',
            'success',
        ]);
    }

    public function testBreakDown()
    {
        $review = Review::factory()->create();

        $this->json(
            'GET',
            'api/private/site/review/break-down/' . $review->school_id,
        )->assertStatus(200)->assertJsonStructure([
            'data' => [
                '*' => $this->getBreakDownStructure()
            ],
            'success',
        ]);
    }

    /**
     * Получить структуру данных отзывов.
     *
     * @return array Массив структуры данных отзывов.
     */
    private function getReviewStructure(): array
    {
        return [
            'id',
            'school_id',
            'course_id',
            'name',
            'title',
            'review',
            'advantages',
            'disadvantages',
            'rating',
            'status',
            'created_at',
            'updated_at',
            'deleted_at',
            'school' => [
                'id',
                'metatag_id',
                'name',
                'header',
                'link',
                'text',
                'rating',
                'site',
                'status',
                'image_logo_id',
                'image_site_id',
                'created_at',
                'updated_at',
                'deleted_at',
            ],
        ];
    }

    /**
     * Получить структуру данных разбивки отзывов.
     *
     * @return array Массив структуры данных разбивки.
     */
    private function getBreakDownStructure(): array
    {
        return [
            'rating',
            'amount'
        ];
    }
}
