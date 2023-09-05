<?php
/**
 * Модуль ядра системы.
 * Этот модуль содержит все классы для работы с ядром системы.
 *
 * @package App\Modules\Core
 */

namespace App\Modules\Core\Sitemap\Parts;

use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Course\Actions\Site\Course\CourseReadAction;
use App\Modules\Course\Entities\Course;
use App\Modules\Course\Enums\Status;
use App\Modules\Direction\Models\Direction;
use Carbon\Carbon;
use Generator;
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
     * @throws ParameterInvalidException
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
                $item->lastmod = $this->getLastmod($result['id'], 'directions-id');

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
     * @param ?string $nameFilter Название фильтра.
     * @param ?int $id ID сущности.
     *
     * @return ?Carbon Дата последней модификации.
     * @throws ParameterInvalidException
     */
    protected function getLastmod(int $id = null, string $nameFilter = null): ?Carbon
    {
        $action = app(CourseReadAction::class);
        $action->forcePrecache = true;
        $action->offset = 0;
        $action->limit = 36;
        $action->sorts = [
            'name' => 'asc',
        ];

        if ($id && $nameFilter) {
            $action->filters = [
                $nameFilter => [$id],
            ];
        }

        $courseRead = $action->run();

        if ($courseRead) {
            $dates = [];
            $dates[] = $courseRead->description?->updated_at;
            $dates[] = $courseRead->description?->metatag->updated_at;

            $dates[] = collect($courseRead->courses)->max(function (Course $course) {
                return $course->updated_at;
            });

            return max($dates);
        }

        return null;
    }
}
