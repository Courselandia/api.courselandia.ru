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
 * Класс действия для получения школ.
 */
class CourseSchoolReadAction extends Action
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
            'schools',
            'site',
            'read',
            $this->filters,
            $this->offset,
            $this->limit,
        );

        unset($this->filters['schools-id']);

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
                $query = Course::select([
                    'id',
                    'school_id'
                ])
                    ->filter($this->filters ?: [])
                    ->with([
                        'school' => function ($query) {
                            $query->select([
                                'schools.id',
                                'schools.name',
                            ])->where('status', true);
                        }
                    ])
                    ->where('status', Status::ACTIVE->value)
                    ->whereHas('school', function ($query) {
                        $query->where('status', true);
                    })
                    ->groupBy([
                        'id',
                        'school_id'
                    ]);

                $items = $query->get();
                $result = [];

                foreach ($items as $item) {
                    if (!isset($result[$item->school->id])) {
                        $result[$item->school->id] = [
                            'id' => $item->school->id,
                            'name' => $item->school->name,
                        ];
                    }
                }

                $result = collect($result)
                    ->values()
                    ->sortBy(function ($school) {
                        return $school['name'];
                    })
                    ->slice($this->offset ?: 0, $this->limit ?: null)
                    ->toArray();

                return Entity::toEntities($result, new CourseItemFilter());
            }
        );
    }
}
