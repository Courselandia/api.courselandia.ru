<?php
/**
 * Модуль Направления.
 * Этот модуль содержит все классы для работы с направлениями.
 *
 * @package App\Modules\Direction
 */

namespace App\Modules\Direction\Actions\Admin;

use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Rep\RepositoryFilter;
use App\Models\Rep\RepositoryQueryBuilder;
use App\Modules\Direction\Repositories\Direction;
use Cache;
use JetBrains\PhpStorm\ArrayShape;
use ReflectionException;
use Util;

/**
 * Класс действия для чтения направлений.
 */
class DirectionReadAction extends Action
{
    /**
     * Репозиторий направлений.
     *
     * @var Direction
     */
    private Direction $direction;

    /**
     * Поиск данных.
     *
     * @var string|null
     */
    public ?string $search = null;

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
     * @param  Direction  $direction  Репозиторий направлений.
     */
    public function __construct(Direction $direction)
    {
        $this->direction = $direction;
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
        $query->setSearch($this->search)
            ->setFilters(RepositoryFilter::getFilters($this->filters))
            ->setSorts($this->sorts)
            ->setOffset($this->offset)
            ->setLimit($this->limit)
            ->setRelations([
                'metatag',
            ]);

        $cacheKey = Util::getKey('direction', 'read', 'count', $query);

        return Cache::tags(['catalog', 'category', 'direction', 'profession'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($query) {
                return [
                    'data' => $this->direction->read($query),
                    'total' => $this->direction->count($query),
                ];
            }
        );
    }
}
