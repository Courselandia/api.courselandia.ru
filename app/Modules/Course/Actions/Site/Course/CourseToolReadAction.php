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
use App\Modules\Tool\Models\Tool;
use Cache;
use Util;
use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Modules\Course\Entities\CourseItemFilter;
use App\Modules\Course\Enums\Status;

/**
 * Класс действия для получения инструмента.
 */
class CourseToolReadAction extends Action
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
        if (isset($this->filters['tools-id'])) {
            $currentFilters = is_array($this->filters['tools-id']) ? $this->filters['tools-id'] : [$this->filters['tools-id']];
            unset($this->filters['tools-id']);
        } else {
            $currentFilters = [];
        }

        $cacheKey = Util::getKey(
            'course',
            'tools',
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
                $query = Tool::select([
                    'tools.id',
                    'tools.link',
                    'tools.name',
                ])
                ->whereHas('courses', function ($query) {
                    $query->select([
                        'courses.id',
                    ])->filter($this->filters ?: [])
                    ->where('status', Status::ACTIVE->value)
                    ->whereHas('school', function ($query) {
                        $query->where('status', true);
                    });
                })
                ->where('status', true);

                if (count($currentFilters)) {
                    $query->orderBy(DB::raw('FIELD(id, ' . implode(', ', array_reverse($currentFilters)) . ')'), 'DESC');
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
