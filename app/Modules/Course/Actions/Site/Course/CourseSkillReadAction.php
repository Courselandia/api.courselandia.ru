<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Actions\Site\Course;

use DB;
use Cache;
use Util;
use Storage;
use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Modules\Skill\Models\Skill;
use App\Modules\Course\Entities\CourseItemFilter;
use App\Modules\Course\Enums\Status;

/**
 * Класс действия для получения навыков.
 */
class CourseSkillReadAction extends Action
{
    /**
     * Фильтрация данных.
     *
     * @var array|null
     */
    private ?array $filters;

    /**
     * Начать выборку.
     *
     * @var int|null
     */
    private ?int $offset;

    /**
     * Лимит выборки.
     *
     * @var int|null
     */
    private ?int $limit;

    /**
     * Отключать не активные.
     *
     * @var bool
     */
    private ?bool $disabled;

    /**
     * Позволить брать данные с файлов.
     *
     * @var bool
     */
    private bool $takeFromFiles;

    /**
     * @param array|null $filters Фильтрация данных.
     * @param int|null $offset Начать выборку.
     * @param int|null $limit Лимит выборки выборку.
     * @param bool|null $disabled Отключать не активные.
     * @param bool $takeFromFiles Позволить брать данные с файлов.
     */
    public function __construct(
        ?array $filters = null,
        ?int $offset = null,
        ?int $limit = null,
        ?bool $disabled = null,
        bool $takeFromFiles = false,
    ) {
        $this->filters = $filters;
        $this->offset = $offset;
        $this->limit = $limit;
        $this->disabled = $disabled;
        $this->takeFromFiles = $takeFromFiles;
    }

    /**
     * Метод запуска логики.
     *
     * @return array<int, CourseItemFilter> Вернет результаты исполнения.
     */
    public function run(): array
    {
        $filters = $this->filters;

        if (isset($filters['skills-id'])) {
            $skillFilters = is_array($filters['skills-id']) ? $filters['skills-id'] : [$filters['skills-id']];
            unset($filters['skills-id']);
        } else {
            $skillFilters = [];
        }

        if (empty($this->filters)) {
            return $this->getPartitionSkills();
        }

        $allSkills = [];

        if ($this->disabled === true) {
            $allSkills = $this->getAllSkills();
        }

        return $this->getSkills($filters, $skillFilters, $allSkills);
    }

    /**
     * Получить часть навыков без учета фильтра.
     *
     * @return array<int, CourseItemFilter> Вернет массив навыков.
     */
    private function getPartitionSkills(): array
    {
        $cacheKey = Util::getKey(
            'course',
            'skills',
            'site',
            'read',
            'part',
            $this->offset,
            $this->limit,
        );

        return Cache::tags(['catalog', 'course'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $query = Skill::select([
                    'skills.id',
                    'skills.link',
                    'skills.name',
                ])
                    ->whereHas('courses', function ($query) {
                        $query->select([
                            'courses.id',
                        ])
                            ->where('status', Status::ACTIVE->value)
                            ->where('has_active_school', true);
                    })
                    ->where('status', true)
                    ->orderBy('name');

                if ($this->offset) {
                    $query->offset($this->offset);
                }

                if ($this->limit) {
                    $query->limit($this->limit);
                }

                return CourseItemFilter::collect($query->get()->toArray());
            }
        );
    }

    /**
     * Вернет все навыки.
     *
     * @return array Массив всех навыков.
     */
    private function getAllSkills(): array
    {
        $cacheKey = Util::getKey(
            'course',
            'skills',
            'site',
            'read',
            'all',
        );

        return Cache::tags(['catalog', 'course'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $path = '/json/skills.json';

                if ($this->takeFromFiles && Storage::drive('public')->exists($path)) {
                    $data = Storage::drive('public')->get($path);

                    return json_decode($data, true);
                }

                return Skill::select([
                    'skills.id',
                    'skills.link',
                    'skills.name',
                ])
                    ->whereHas('courses', function ($query) {
                        $query->select([
                            'courses.id',
                        ])
                            ->where('status', Status::ACTIVE->value)
                            ->where('has_active_school', true);
                    })
                    ->where('status', true)
                    ->orderBy('name')
                    ->get()
                    ->toArray();
            }
        );
    }

    /**
     * Получить навыки с учетом фильтров.
     *
     * @param array $filters Все фильтры.
     * @param array $skillFilters Фильтры навыков.
     * @param array $allSkills Все фильтры.
     * @return array<int, CourseItemFilter> Вернет массив фильтров.
     */
    private function getSkills(array $filters, array $skillFilters, array $allSkills): array
    {
        $cacheKey = Util::getKey(
            'course',
            'skills',
            'site',
            'read',
            $filters,
            $skillFilters,
            $this->offset,
            $this->limit,
            $this->disabled,
        );

        return Cache::tags(['catalog', 'course'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($skillFilters, $filters, $allSkills) {
                if (!empty($filters) || !$this->disabled) {
                    $query = Skill::select([
                        'skills.id',
                        'skills.link',
                        'skills.name',
                    ])
                        ->whereHas('courses', function ($query) use ($filters) {
                            $query->select([
                                'courses.id',
                            ])
                                ->filter($filters ?: [])
                                ->where('status', Status::ACTIVE->value)
                                ->where('has_active_school', true);
                        })
                        ->where('status', true)
                        ->orderBy('name');

                    if (count($skillFilters) && !$this->disabled) {
                        $query->orderBy(DB::raw('FIELD(id, ' . implode(', ', array_reverse($skillFilters)) . ')'),
                            'DESC');
                    }

                    if (!$this->disabled) {
                        if ($this->offset) {
                            $query->offset($this->offset);
                        }

                        if ($this->limit) {
                            $query->limit($this->limit);
                        }
                    }

                    $activeSkills = $query->get()->toArray();
                } else {
                    $activeSkills = $allSkills;
                }

                if ($this->disabled) {
                    if (count($activeSkills) !== count($allSkills)) {
                        $activeSkillsWithKey = [];

                        for ($i = 0; $i < count($activeSkills); $i++) {
                            $activeSkillsWithKey[$activeSkills[$i]['id']] = true;
                        }

                        for ($i = 0; $i < count($allSkills); $i++) {
                            $allSkills[$i]['disabled'] = !isset($activeSkillsWithKey[$allSkills[$i]['id']]);
                        }
                    } else {
                        $allSkills = collect($allSkills)->map(function ($item) {
                            $item['disabled'] = false;

                            return $item;
                        })->toArray();
                    }

                    $result = collect($allSkills)
                        ->sortBy(function ($item) use ($skillFilters) {
                            if (count($skillFilters) && in_array($item['id'], $skillFilters)) {
                                $weight = 1;
                            } else {
                                if (isset($item['disabled']) && !$item['disabled']) {
                                    $weight = 2;
                                } else {
                                    $weight = 3;
                                }
                            }

                            return $weight . ' - ' . $item['name'];
                        })
                        ->slice($this->offset, $this->limit)
                        ->values()
                        ->toArray();

                    return CourseItemFilter::collect($result);
                }

                return CourseItemFilter::collect($activeSkills);
            }
        );
    }
}
