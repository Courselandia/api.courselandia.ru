<?php
/**
 * Модуль Логирование.
 * Этот модуль содержит все классы для работы с логированием.
 *
 * @package App\Modules\Log
 */

namespace App\Modules\Log\Actions\Admin;

use App\Models\Action;
use App\Modules\Log\Repositories\Log;

/**
 * Класс действия для удаления логов.
 */
class LogDestroyAction extends Action
{
    /**
     * Репозиторий логирования.
     *
     * @var Log
     */
    private Log $log;

    /**
     * Массив ID логов.
     *
     * @var int[]|string[]
     */
    public ?array $ids = null;

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
     * @return bool Вернет результаты исполнения.
     */
    public function run(): bool
    {
        if ($this->ids) {
            for ($i = 0; $i < count($this->ids); $i++) {
                $this->log->destroy($this->ids[$i]);
            }
        }

        return true;
    }
}
