<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Actions\Site\Course;

use DB;
use App\Models\Entity;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Profession\Models\Profession;
use Cache;
use Util;
use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Modules\Course\Entities\CourseItemFilter;
use App\Modules\Course\Enums\Status;

/**
 * Класс действия для получения профессий.
 */
class CourseProfessionReadAction extends Action
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
        $filters = $this->filters;

        if (isset($filters['professions-id'])) {
            $professionFilters = is_array($filters['professions-id']) ? $filters['professions-id'] : [$filters['professions-id']];
            unset($filters['professions-id']);
        } else {
            $professionFilters = [];
        }

        $cacheKey = Util::getKey(
            'course',
            'professions',
            'site',
            'read',
            $filters,
            $this->offset,
            $this->limit,
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
            function () use ($professionFilters, $filters) {
                $query = Profession::select([
                    'professions.id',
                    'professions.link',
                    'professions.name',
                ])
                ->whereHas('courses', function ($query) use ($filters) {
                    $query->select([
                        'courses.id',
                    ])
                    ->filter($filters ?: [])
                    ->where('status', Status::ACTIVE->value)
                    ->whereHas('school', function ($query) {
                        $query->where('status', true);
                    });
                })
                ->where('status', true);

                if (count($professionFilters)) {
                    $query->orderBy(DB::raw('FIELD(id, ' . implode(', ', array_reverse($professionFilters)) . ')'), 'DESC');
                }

                $query->orderBy('name');

                if ($this->offset) {
                    $query->offset($this->offset);
                }

                if ($this->limit) {
                    $query->limit($this->limit);
                }

                $result = $query->get()->toArray();

                return Entity::toEntities($result, new CourseItemFilter());
            }
        );
    }
}
