<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Actions\Site\Course;

use App\Modules\Course\Entities\CourseItemDirectionFilter;
use DB;
use App\Models\Entity;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Direction\Models\Direction;
use Cache;
use Util;
use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Modules\Course\Entities\CourseItemFilter;
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
     * Добавить ли категории к направлениям.
     *
     * @var bool
     */
    public ?bool $withCategories = false;

    /**
     * Добавить информацию о количестве курсов в направлении.
     *
     * @var bool
     */
    public ?bool $withCount = false;

    /**
     * Метод запуска логики.
     *
     * @return CourseItemFilter[] Вернет результаты исполнения.
     * @throws ParameterInvalidException
     */
    public function run(): array
    {
        if (isset($this->filters['directions-id'])) {
            $currentFilters = is_array(
                $this->filters['directions-id']
            ) ? $this->filters['directions-id'] : [$this->filters['directions-id']];
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
            $this->withCategories,
            $this->withCount,
        );

        return Cache::tags([
            'course',
            'direction',
            'profession',
            'category',
            'skill',
            'teacher',
            'tool',
            'process',
            'employment',
        ])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($currentFilters) {
                $query = Direction::select([
                    'directions.id',
                    'directions.name',
                    'directions.link',
                    'directions.weight',
                ])
                ->whereHas('courses', function ($query) {
                    $query->select([
                        'courses.id',
                    ])
                    ->filter($this->filters ?: [])
                    ->where('status', Status::ACTIVE->value)
                    ->whereHas('school', function ($query) {
                        $query->where('status', true);
                    });
                })
                ->where('status', true);

                $query->orderBy('weight');

                if ($this->offset) {
                    $query->offset($this->offset);
                }

                if ($this->limit) {
                    $query->limit($this->limit);
                }

                if ($this->withCategories) {
                    $query->with('categories');
                }

                if ($this->withCount) {
                    $query->withCount([
                        'courses' => function ($query) {
                            $query
                            ->filter($this->filters ?: [])
                            ->where('courses.status', Status::ACTIVE->value)
                            ->whereHas('school', function ($query) {
                                $query->where('schools.status', true);
                            });
                        }
                    ]);
                }

                $result = $query->get()->toArray();

                if ($this->withCount) {
                    foreach ($result as $key => $value) {
                        $result[$key]['count'] = $value['courses_count'];

                        unset($result[$key]['courses_count']);
                    }
                }

                if ($this->withCategories) {
                    return Entity::toEntities($result, new CourseItemDirectionFilter());
                }

                return Entity::toEntities($result, new CourseItemFilter());
            }
        );
    }
}
