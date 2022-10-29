<?php
/**
 * Модуль Учителей.
 * Этот модуль содержит все классы для работы с учителями.
 *
 * @package App\Modules\Teacher
 */

namespace App\Modules\Teacher\Actions\Admin\Teacher;

use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Rep\RepositoryFilter;
use App\Models\Rep\RepositoryQueryBuilder;
use App\Modules\Teacher\Repositories\Teacher;
use Cache;
use JetBrains\PhpStorm\ArrayShape;
use ReflectionException;
use Util;

/**
 * Класс действия для чтения учителя.
 */
class TeacherReadAction extends Action
{
    /**
     * Репозиторий учителя.
     *
     * @var Teacher
     */
    private Teacher $teacher;

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
     * Конструктор.
     *
     * @param  Teacher  $teacher  Репозиторий учителя.
     */
    public function __construct(Teacher $teacher)
    {
        $this->teacher = $teacher;
    }

    /**
     * Метод запуска логики.
     *
     * @return mixed Вернет результаты исполнения.
     * @throws ParameterInvalidException|ReflectionException
     */
    #[ArrayShape(['data' => 'array', 'total' => 'int'])] public function run(): array
    {
        $query = new RepositoryQueryBuilder();
        $query->setFilters(RepositoryFilter::getFilters($this->filters))
            ->setSorts($this->sorts)
            ->setOffset($this->offset)
            ->setLimit($this->limit)
            ->setRelations([
                'metatag',
            ]);

        $cacheKey = Util::getKey('teacher', 'read', 'count', $query);

        return Cache::tags(['catalog', 'teacher', 'direction', 'school'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($query) {
                return [
                    'data' => $this->teacher->read($query),
                    'total' => $this->teacher->count($query),
                ];
            }
        );
    }
}
