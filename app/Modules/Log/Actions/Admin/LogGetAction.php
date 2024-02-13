<?php
/**
 * Модуль Логирование.
 * Этот модуль содержит все классы для работы с логированием.
 *
 * @package App\Modules\Log
 */

namespace App\Modules\Log\Actions\Admin;

use App\Models\Action;
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
     * @var int|string
     */
    private int|string $id;

    /**
     * Конструктор.
     *
     * @param Log $log Репозиторий логирования.
     * @param int|string $id ID лога.
     */
    public function __construct(Log $log, int|string $id)
    {
        $this->log = $log;
        $this->id = $id;
    }

    /**
     * Метод запуска логики.
     *
     * @return LogEntity|null Вернет результаты исполнения.
     */
    public function run(): ?LogEntity
    {
        if ($this->id) {
            return $this->log->get($this->id);
        }

        return null;
    }
}

