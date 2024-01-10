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
