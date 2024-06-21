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
use App\Modules\Course\Enums\Status;
use App\Modules\Profession\Models\Profession;
use App\Modules\Course\Entities\CourseItemFilter;

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

        if (isset($filters['professions-id'])) {
            $professionFilters = is_array($filters['professions-id']) ? $filters['professions-id'] : [$filters['professions-id']];
            unset($filters['professions-id']);
        } else {
            $professionFilters = [];
        }

        if (empty($this->filters)) {
            return $this->getPartitionProfessions();
        }

        $allProfessions = [];

        if ($this->disabled === true) {
            $allProfessions = $this->getAllProfessions();
        }

        return $this->getProfessions($filters, $professionFilters, $allProfessions);
    }

    /**
     * Получить часть профессий без учета фильтра.
     *
     * @return array<int, CourseItemFilter> Вернет массив фильтров.
     */
    private function getPartitionProfessions(): array
    {
        $cacheKey = Util::getKey(
            'course',
            'professions',
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
                $query = Profession::select([
                    'professions.id',
                    'professions.link',
                    'professions.name',
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
     * Вернет все профессии.
     *
     * @return array Массив всех профессий.
     */
    private function getAllProfessions(): array
    {
        $cacheKey = Util::getKey(
            'course',
            'professions',
            'site',
            'read',
            'all',
        );

        return Cache::tags(['catalog', 'course'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $path = '/json/professions.json';

                if ($this->takeFromFiles && Storage::drive('public')->exists($path)) {
                    $data = Storage::drive('public')->get($path);

                    return json_decode($data, true);
                }

                return Profession::select([
                    'professions.id',
                    'professions.link',
                    'professions.name',
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
     * Получить профессия с учетом фильтров.
     *
     * @param array $filters Все фильтры.
     * @param array $professionFilters Фильтры профессий.
     * @param array $allProfessions Все профессии.
     *
     * @return array<int, CourseItemFilter> Вернет массив фильтров.
     */
    private function getProfessions(array $filters, array $professionFilters, array $allProfessions): array
    {
        $cacheKey = Util::getKey(
            'course',
            'professions',
            'site',
            'read',
            $filters,
            $professionFilters,
            $this->offset,
            $this->limit,
            $this->disabled,
        );

        return Cache::tags(['catalog', 'course'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($professionFilters, $filters, $allProfessions) {
                if (!empty($filters) || !$this->disabled) {
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
                                ->where('has_active_school', true);
                        })
                        ->where('status', true)
                        ->orderBy('name');

                    if (count($professionFilters) && !$this->disabled) {
                        $query->orderBy(DB::raw('FIELD(id, ' . implode(', ', array_reverse($professionFilters)) . ')'),
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

                    $activeProfessions = $query->get()->toArray();
                } else {
                    $activeProfessions = $allProfessions;
                }

                if ($this->disabled) {
                    if (count($activeProfessions) !== count($allProfessions)) {
                        $activeProfessionsWithKey = [];

                        for ($i = 0; $i < count($activeProfessions); $i++) {
                            $activeProfessionsWithKey[$activeProfessions[$i]['id']] = true;
                        }

                        for ($i = 0; $i < count($allProfessions); $i++) {
                            $allProfessions[$i]['disabled'] = !isset($activeProfessionsWithKey[$allProfessions[$i]['id']]);
                        }
                    } else {
                        $allProfessions = collect($allProfessions)->map(function ($item) {
                            $item['disabled'] = false;

                            return $item;
                        })->toArray();
                    }

                    $result = collect($allProfessions)
                        ->sortBy(function ($item) use ($professionFilters) {
                            if (count($professionFilters) && in_array($item['id'], $professionFilters)) {
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

                return CourseItemFilter::collect($activeProfessions);
            }
        );
    }
}
