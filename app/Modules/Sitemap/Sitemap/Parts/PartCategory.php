<?php
/**
 * Модуль sitemap.xml.
 * Этот модуль содержит все классы для работы с генерацией sitemap.xml.
 *
 * @package App\Modules\Sitemap
 */

namespace App\Modules\Sitemap\Sitemap\Parts;

use App\Modules\Category\Models\Category;
use App\Modules\Course\Enums\Status;
use App\Modules\Sitemap\Sitemap\Item;
use Generator;
use Illuminate\Database\Eloquent\Builder;

/**
 * Генератор для категорий.
 */
class PartCategory extends PartDirection
{
    /**
     * Вернет количество генерируемых элементов.
     *
     * @return int Количество элементов.
     */
    public function count(): int
    {
        return $this->getQuery()->count();
    }

    /**
     * Генерация элемента.
     *
     * @return Generator<Item> Генерируемый элемент.
     */
    public function generate(): Generator
    {
        $count = $this->count();

        for ($i = 0; $i <= $count; $i++) {
            $result = $this->getQuery()
                ->limit(1)
                ->offset($i)
                ->first()
                ?->toArray();

            if ($result) {
                $item = new Item();
                $item->path = '/courses/category/' . $result['link'];
                $item->priority = 0.8;
                $item->lastmod = $this->getLastmod('category', $result['link']);

                yield $item;
            }
        }
    }

    /**
     * Запрос для получения данных.
     *
     * @return Builder Запрос.
     */
    private function getQuery(): Builder
    {
        return Category::select([
            'categories.id',
            'categories.link',
        ])
        ->whereHas('courses', function ($query) {
            $query->select([
                'courses.id',
            ])
            ->where('status', Status::ACTIVE->value)
            ->whereHas('school', function ($query) {
                $query->active()
                    ->hasCourses();
            });
        })
        ->where('status', true)
        ->orderBy('name');
    }
}
