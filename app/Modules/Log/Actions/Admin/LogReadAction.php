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
use App\Modules\Log\Repositories\Log;

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
     * Конструктор.
     *
     * @param Log $log Репозиторий обратной связи.
     * @param array|null $sorts Сортировка данных.
     * @param array|null $filters Фильтрация данных.
     * @param int|null $offset Начать выборку.
     * @param int|null $limit Лимит выборки выборку.
     */
    public function __construct(
        Log    $log,
        array  $sorts = null,
        ?array $filters = null,
        ?int   $offset = null,
        ?int   $limit = null
    )
    {
        $this->log = $log;
        $this->sorts = $sorts;
        $this->filters = $filters;
        $this->offset = $offset;
        $this->limit = $limit;
    }

    /**
     * Метод запуска логики.
     *
     * @return array Вернет результаты исполнения.
     * @throws ParameterInvalidException
     */
    public function run(): array
    {
        return [
            'data' => $this->log->read($this->filters, $this->sorts, $this->offset, $this->limit),
            'total' => $this->log->count($this->filters),
        ];
    }
}
