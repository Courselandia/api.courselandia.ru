<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Actions\Site\Course;

use App\Models\Entity;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Course\Helpers\SortFilter;
use Cache;
use Util;
use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Modules\Course\Entities\CourseItemFilter;
use App\Modules\Course\Models\Course;
use App\Modules\Course\Enums\Status;

/**
 * Класс действия для получения направлений.
 */
class CourseDirectionReadAction extends Action
{
    /**
     * Фильтрация данных.
     *
     * @var array|null
     */
    public ?array $filters = null;

    /**
     * Начать выборку.
     *
     * @var int|null
     */
    public ?int $offset = null;

    /**
     * Лимит выборки.
     *
     * @var int|null
     */
    public ?int $limit = null;

    /**
     * Метод запуска логики.
     *
     * @return CourseItemFilter[] Вернет результаты исполнения.
     * @throws ParameterInvalidException
     */
    public function run(): array
    {
        if (isset($this->filters['directions-id'])) {
            $currentFilters = is_array($this->filters['directions-id']) ? $this->filters['directions-id'] : [$this->filters['directions-id']];
            unset($this->filters['directions-id']);
        } else {
            $currentFilters = [];
        }

        $cacheKey = Util::getKey(
            'course',
            'directions',
            'site',
            'read',
            $this->filters,
            $this->offset,
            $this->limit,
            $currentFilters,
        );

        return Cache::tags([
            'course',
            'direction',
            'profession',
            'category',
            'skill',
            'teacher',
            'tool',
        ])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($currentFilters) {
                $query = Course::select('id')
                    ->filter($this->filters ?: [])
                    ->with([
                        'directions' => function ($query) {
                            $query->select([
                                'directions.id',
                                'directions.name',
                                'directions.link',
                                'directions.weight',
                            ])->where('status', true);
                        }
                    ])
                    ->where('status', Status::ACTIVE->value)
                    ->whereHas('school', function ($query) {
                        $query->where('status', true);
                    });

                $items = $query->get();
                $result = [];

                foreach ($items as $item) {
                    foreach ($item->directions as $direction) {
                        if (!isset($result[$direction->id])) {
                            $result[$direction->id] = [
                                'id' => $direction->id,
                                'name' => $direction->name,
                                'link' => $direction->link,
                                'weight' => $direction->weight
                            ];
                        }
                    }
                }

                $result = SortFilter::run($result, $currentFilters, $this->offset, $this->limit);

                return Entity::toEntities($result, new CourseItemFilter());
            }
        );
    }
}
