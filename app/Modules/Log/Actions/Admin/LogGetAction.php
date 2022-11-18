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
use App\Models\Rep\RepositoryQueryBuilder;
use App\Modules\Log\Entities\Log as LogEntity;
use App\Modules\Log\Repositories\Log;

/**
 * Класс действия для получения лога.
 */
class LogGetAction extends Action
{
    /**
     * Репозиторий логирования.
     *
     * @var Log
     */
    private Log $log;

    /**
     * ID лога.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * Конструктор.
     *
     * @param  Log  $log  Репозиторий логирования.
     */
    public function __construct(Log $log)
    {
        $this->log = $log;
    }

    /**
     * Метод запуска логики.
     *
     * @return LogEntity|null Вернет результаты исполнения.
     * @throws ParameterInvalidException
     */
    public function run(): ?LogEntity
    {
        if ($this->id) {
            return $this->log->get(new RepositoryQueryBuilder($this->id));
        }

        return null;
    }
}

