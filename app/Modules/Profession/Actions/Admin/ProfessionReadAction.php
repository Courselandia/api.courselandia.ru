<?php
/**
 * Модуль Профессии.
 * Этот модуль содержит все классы для работы с профессиями.
 *
 * @package App\Modules\Profession
 */

namespace App\Modules\Profession\Actions\Admin;

use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Rep\RepositoryFilter;
use App\Models\Rep\RepositoryQueryBuilder;
use App\Modules\Profession\Repositories\Profession;
use Cache;
use JetBrains\PhpStorm\ArrayShape;
use ReflectionException;
use Util;

/**
 * Класс действия для чтения профессий.
 */
class ProfessionReadAction extends Action
{
    /**
     * Репозиторий профессий.
     *
     * @var Profession
     */
    private Profession $profession;

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
     * @param  Profession  $profession  Репозиторий профессий.
     */
    public function __construct(Profession $profession)
    {
        $this->profession = $profession;
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

        $cacheKey = Util::getKey('profession', 'read', 'count', $query);

        return Cache::tags(['catalog', 'category', 'direction', 'profession'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($query) {
                return [
                    'data' => $this->profession->read($query),
                    'total' => $this->profession->count($query),
                ];
            }
        );
    }
}
