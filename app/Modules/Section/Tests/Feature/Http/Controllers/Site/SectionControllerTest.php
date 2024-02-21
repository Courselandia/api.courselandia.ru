<?php
/**
 * Модуль Разделов.
 * Этот модуль содержит все классы для работы с разделами каталога.
 *
 * @package App\Modules\Section
 */

namespace App\Modules\Section\Tests\Feature\Http\Controllers\Site;

use App\Modules\Direction\Models\Direction;
use App\Modules\Section\Models\Section;
use Tests\TestCase;

/**
 * Тестирование: Класс контроллер для разделов.
 */
class SectionControllerTest extends TestCase
{
    /**
     * Получение записи.
     *
     * @return void
     */
    public function testLink(): void
    {
        $direction = Direction::factory()->create();
        $section = Section::factory()->create();

        $this->json(
            'GET',
            'api/private/site/section/link',
            [
                'free' => $section->free,
                'level' => $section->level,
                'items' => [
                    [
                        'type' => 'direction',
                        'link' => $direction->link,
                    ],
                ],
            ],
        )->assertStatus(200)->assertJsonStructure([
            'data' => $this->getSectionStructure(),
            'success',
        ]);
    }

    /**
     * Получение записи с ошибкой при отсутствии записи.
     *
     * @return void
     */
    public function testLinkNotExist(): void
    {
        $section = Section::factory()->create();

        $this->json(
            'GET',
            'api/private/site/section/link',
            [
                'free' => $section->free,
                'level' => $section->level,
                'items' => [
                    [
                        'type' => 'direction',
                        'link' => 'test',
                    ],
                ],
            ],
        )->assertStatus(200)->assertJsonStructure([
            'data' => $this->getSectionStructure(),
            'success',
        ]);
    }

    /**
     * Получить структуру данных категории.
     *
     * @return array Массив структуры данных категории.
     */
    private function getSectionStructure(): array
    {
        return [
            'id',
            'metatag_id',
            'name',
            'header',
            'text',
            'additional',
            'level',
            'free',
            'status',
            'created_at',
            'updated_at',
            'deleted_at',
            'metatag',
            'items' => [
                '*' => [
                    'id',
                    'section_id',
                    'weight',
                    'itemable_id',
                    'itemable_type',
                    'itemable',
                ],
            ],
        ];
    }
}
