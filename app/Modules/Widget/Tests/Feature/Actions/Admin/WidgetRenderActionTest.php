<?php
/**
 * Модуль Виджетов.
 * Этот модуль содержит все классы для работы с виджетами, которые можно использовать в публикациях.
 *
 * @package App\Modules\Widget
 */

namespace App\Modules\Widget\Tests\Feature\Actions\Admin;

use Util;
use Throwable;
use Carbon\Carbon;
use Tests\TestCase;
use Faker\Factory as Faker;
use App\Modules\Publication\Actions\Admin\Publication\PublicationCreateAction;
use App\Modules\Publication\Data\Actions\Admin\PublicationCreate;
use App\Modules\Widget\Actions\Admin\WidgetRenderAction;
use App\Modules\Collection\Actions\Admin\Collection\CollectionCreateAction;
use App\Modules\Collection\Data\CollectionCreate;
use App\Modules\Direction\Models\Direction;

/**
 * Тестирование: Отображение виджета в тексте.
 */
class WidgetRenderActionTest extends TestCase
{
    /**
     * Тестируем виджет: Публикации - Читайте так же.
     *
     * @return void
     * @throws Throwable
     */
    public function testPublicationAlso(): void
    {
        $faker = Faker::create();

        $actionPublicationCreate = new PublicationCreateAction(
            PublicationCreate::from([
                'published_at' => Carbon::now()->addMonths(-5),
                'header' => $faker->text(191),
                'link' => Util::latin($faker->text(191)),
                'anons' => $faker->text(250),
                'article' => $faker->text(1500),
                'status' => true,
            ])
        );

        $publication = $actionPublicationCreate->run();

        $text = '<component id="' . $publication->id . '" is="publications-also"></component>';
        $actionRender = new WidgetRenderAction($text);
        $result = $actionRender->run();

        $this->assertStringContainsString($publication->header, $result);
    }

    /**
     * Тестируем виджет: Коллекции - Смотрите так же.
     *
     * @return void
     * @throws Throwable
     */
    public function testCollectionAlso(): void
    {
        $faker = Faker::create();
        $direction = Direction::factory()->create();

        $actionCollectionCreate = new CollectionCreateAction(
            CollectionCreate::from([
                'direction_id' => $direction->id,
                'name' => $faker->text(191),
                'link' => Util::latin($faker->text(191)),
                'text' => $faker->text(1500),
                'additional' => $faker->text(1500),
                'amount' => 10,
                'sort_field' => 'name',
                'sort_direction' => 'ASC',
                'copied' => true,
                'status' => true,
            ])
        );

        $collection = $actionCollectionCreate->run();

        $text = '<component id="' . $collection->id . '" is="collections-also"></component>';
        $actionRender = new WidgetRenderAction($text);
        $result = $actionRender->run();

        $this->assertStringContainsString($collection->name, $result);
    }
}
