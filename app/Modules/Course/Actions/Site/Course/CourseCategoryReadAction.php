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
use Cache;
use Util;
use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Modules\Course\Entities\CourseItemFilter;
use App\Modules\Course\Models\Course;
use App\Modules\Course\Enums\Status;

/**
 * Класс действия для получения категорий.
 */
class CourseCategoryReadAction extends Action
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
        $cacheKey = Util::getKey(
            'course',
            'categories',
            'site',
            'read',
            $this->filters,
            $this->offset,
            $this->limit,
        );

        unset($this->filters['categories-id']);

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
            function () {
                $query = Course::select('id')
                    ->filter($this->filters ?: [])
                    ->with([
                        'categories' => function ($query) {
                            $query->select([
                                'categories.id',
                                'categories.name',
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
                    foreach ($item->categories as $category) {
                        if (!isset($result[$category->id])) {
                            $result[$category->id] = [
                                'id' => $category->id,
                                'name' => $category->name,
                            ];
                        }
                    }
                }

                $result = collect($result)
                    ->values()
                    ->sortBy(function ($category) {
                        return $category['name'];
                    })
                    ->slice($this->offset ?: 0, $this->limit ?: null)
                    ->toArray();

                return Entity::toEntities($result, new CourseItemFilter());
            }
        );
    }
}
