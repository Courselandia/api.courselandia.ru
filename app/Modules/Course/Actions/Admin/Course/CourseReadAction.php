<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Actions\Admin\Course;

use App\Modules\Course\Entities\Course as CourseEntity;
use Cache;
use Util;
use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Modules\Course\Models\Course;

/**
 * Класс действия для чтения курсов.
 */
class CourseReadAction extends Action
{
    /**
     * Сортировка данных.
     *
     * @var array|null
     */
    private ?array $sorts;

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
     * Лимит выборки выборку.
     *
     * @var int|null
     */
    private ?int $limit;

    /**
     * ID учителя.
     *
     * @var int|string|null
     */
    private int|string|null $teacherId;

    /**
     * @param array|null $sorts Сортировка данных.
     * @param array|null $filters Фильтрация данных.
     * @param int|null $offset Начать выборку.
     * @param int|null $limit Лимит выборки выборку.
     * @param int|string|null $teacherId ID учителя.
     */
    public function __construct(
        array           $sorts = null,
        ?array          $filters = null,
        ?int            $offset = null,
        ?int            $limit = null,
        int|string|null $teacherId = null,
    )
    {
        $this->sorts = $sorts;
        $this->filters = $filters;
        $this->offset = $offset;
        $this->limit = $limit;
        $this->teacherId = $teacherId;
    }

    /**
     * Метод запуска логики.
     *
     * @return mixed Вернет результаты исполнения.
     */
    public function run(): array
    {
        $cacheKey = Util::getKey(
            'course',
            'admin',
            'read',
            'count',
            $this->sorts,
            $this->filters,
            $this->offset,
            $this->limit,
            $this->teacherId,
            'school',
            'directions',
            'professions',
        );

        return Cache::tags(['catalog', 'course'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $query = Course::filter($this->filters ?: [])
                    ->with([
                        'school',
                        'directions',
                        'professions',
                    ]);

                $queryCount = $query->clone();

                $query->sorted($this->sorts ?: []);

                if ($this->offset) {
                    $query->offset($this->offset);
                }

                if ($this->limit) {
                    $query->limit($this->limit);
                }

                if ($this->teacherId) {
                    $query->whereHas('teachers', function ($query) {
                        $query->where('teachers.id', $this->teacherId);
                    });
                }

                $items = $query->get()->toArray();

                return [
                    'data' => CourseEntity::collect($items),
                    'total' => $queryCount->count(),
                ];
            }
        );
    }
}
