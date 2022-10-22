<?php
/**
 * Модуль Логирование.
 * Этот модуль содержит все классы для работы с логированием.
 *
 * @package App\Modules\Log
 */

namespace App\Modules\Log\Actions\Admin;

use App\Models\Action;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Rep\RepositoryFilter;
use App\Models\Rep\RepositoryQueryBuilder;
use App\Modules\Log\Repositories\Log;
use JetBrains\PhpStorm\ArrayShape;
use ReflectionException;

/**
 * Класс действия для чтения логов.
 */
class LogReadAction extends Action
{
    /**
     * Репозиторий обратной связи.
     *
     * @var Log
     */
    private Log $log;

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
     * @param  Log  $log  Репозиторий обратной связи.
     */
    public function __construct(Log $log)
    {
        $this->log = $log;
    }

    /**
     * Метод запуска логики.
     *
     * @return array Вернет результаты исполнения.
     * @throws ParameterInvalidException|ReflectionException
     */
    #[ArrayShape(['data' => 'array', 'total' => 'int'])] public function run(): array
    {
        $query = new RepositoryQueryBuilder();
        $query->setSearch($this->search)
            ->setFilters(RepositoryFilter::getFilters($this->filters))
            ->setSorts($this->sorts)
            ->setOffset($this->offset)
            ->setLimit($this->limit);

        return [
            'data' => $this->log->read($query),
            'total' => $this->log->count($query),
        ];
    }
}
