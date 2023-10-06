<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Actions\Admin\Course;

use App\Models\Entity;
use App\Modules\Course\Entities\Course as CourseEntity;
use Cache;
use Illuminate\Cache\Events\CacheHit;
use Util;
use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Course\Models\Course;
use JetBrains\PhpStorm\ArrayShape;

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
    public ?array $sorts = null;

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
     * Лимит выборки выборку.
     *
     * @var int|null
     */
    public ?int $limit = null;

    /**
     * ID учителя.
     *
     * @var int|string|null
     */
    public int|string|null $teacherId = null;

    /**
     * Метод запуска логики.
     *
     * @return mixed Вернет результаты исполнения.
     * @throws ParameterInvalidException
     */
    #[ArrayShape(['data' => 'array', 'total' => 'int'])] public function run(): array
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
            'review',
            'teacher',
        ])->remember(
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
                    'data' => Entity::toEntities($items, new CourseEntity()),
                    'total' => $queryCount->count(),
                ];
            }
        );
    }
}
