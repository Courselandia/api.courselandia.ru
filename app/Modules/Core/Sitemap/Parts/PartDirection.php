<?php
/**
 * Модуль ядра системы.
 * Этот модуль содержит все классы для работы с ядром системы.
 *
 * @package App\Modules\Core
 */

namespace App\Modules\Core\Sitemap\Parts;

use Storage;
use Generator;
use Carbon\Carbon;
use App\Modules\Course\Enums\Status;
use App\Modules\Direction\Models\Direction;
use App\Modules\Core\Sitemap\Item;
use App\Modules\Core\Sitemap\Part;
use Illuminate\Database\Eloquent\Builder;

/**
 * Генератор для направлений.
 */
class PartDirection extends Part
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
                $item->path = '/courses/direction/' . $result['link'];
                $item->priority = 0.8;
                $item->lastmod = $this->getLastmod('directions', $result['link']);

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
        return Direction::select([
            'directions.id',
            'directions.link',
        ])
        ->whereHas('courses', function ($query) {
            $query->select([
                'courses.id',
            ])
            ->where('status', Status::ACTIVE->value)
            ->whereHas('school', function ($query) {
                $query->where('status', true);
            });
        })
        ->where('status', true)
        ->orderBy('weight');
    }

    /**
     * Дата последней модификации страницы.
     *
     * @param ?string $directory Название директории.
     * @param ?string $link Ссылка на файл.
     *
     * @return ?Carbon Дата последней модификации.
     */
    protected function getLastmod(string $directory = null, string $link = null): ?Carbon
    {
        if ($directory && $link) {
            $path = '/json/courses/' . $directory . '/' . $link . '.json';
        } else {
            $path = '/json/courses.json';
        }

        if (Storage::drive('public')->exists($path)) {
            $json = Storage::drive('public')->get($path);
            $data = json_decode($json, true);

            $dates = [];

            if (isset($data['data']['description']['updated_at'])) {
                $dates[] = Carbon::parse($data['data']['description']['updated_at']);
            }

            if (isset($data['data']['description']['metatag']['updated_at'])) {
                $dates[] = Carbon::parse($data['data']['description']['metatag']['updated_at']);
            }

            $dates[] = collect($data['data']['courses'])->max(function (array $course) {
                return Carbon::parse($course['updated_at']);
            });

            return max($dates);
        }

        return null;
    }
}
