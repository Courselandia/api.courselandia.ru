<?php
/**
 * Модуль sitemap.xml.
 * Этот модуль содержит все классы для работы с генерацией sitemap.xml.
 *
 * @package App\Modules\Sitemap
 */

namespace App\Modules\Sitemap\Sitemap\Parts;

use Carbon\Carbon;
use Config;
use Generator;
use App\Modules\Section\Models\Section;
use App\Modules\Sitemap\Sitemap\Item;
use Illuminate\Database\Eloquent\Builder;

/**
 * Генератор для разделов.
 */
class PartSection extends PartDirection
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
            $section = $this->getQuery()
                ->limit(1)
                ->offset($i)
                ->first()
                ?->toArray();

            if ($section) {
                $item = new Item();
                $item->path = '/courses' . $this->getUrl($section);
                $item->priority = 0.8;
                $item->lastmod = Carbon::parse($section['updated_at']);

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
        return Section::active()
            ->with([
                'items.itemable',
            ])
            ->orderBy('id');
    }

    /**
     * Получение ссылки на раздел.
     *
     * @param array $section Данные раздела.
     * @return string|null URL раздела.
     */
    private function getUrl(array $section): string|null
    {
        $items = Config::get('section.items');

        if (isset($section['items'][0]['itemable']['link'])) {
            $url = Config::get('app.url');
            $sectionName = array_search($section['items'][0]['itemable_type'], $items);
            $url .= '/' . $sectionName . '/' . $section['items'][0]['itemable']['link'];

            if (isset($section['items'][1]['itemable']['link'])) {
                $sectionName = array_search($section['items'][1]['itemable_type'], $items);
                $url .= '/' . $sectionName . '/' . $section['items'][1]['itemable']['link'];
            }

            if ($section['level']) {
                $url .= '/level/' . $section['level'];
            }

            if ($section['free']) {
                $url .= '/free';
            }

            return $url;
        }

        return null;
    }
}
