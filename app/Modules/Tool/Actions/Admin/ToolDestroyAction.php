<?php
/**
 * Модуль Инструментов.
 * Этот модуль содержит все классы для работы с инструментами.
 *
 * @package App\Modules\Tool
 */

namespace App\Modules\Tool\Actions\Admin;

use App\Models\Action;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Tool\Repositories\Tool;
use Cache;

/**
 * Класс действия для удаления инструмента.
 */
class ToolDestroyAction extends Action
{
    /**
     * Репозиторий инструментов.
     *
     * @var Tool
     */
    private Tool $tool;

    /**
     * Массив ID пользователей.
     *
     * @var int[]|string[]
     */
    public ?array $ids = null;

    /**
     * Конструктор.
     *
     * @param  Tool  $tool  Репозиторий инструментов.
     */
    public function __construct(Tool $tool)
    {
        $this->tool = $tool;
    }

    /**
     * Метод запуска логики.
     *
     * @return bool Вернет результаты исполнения.
     * @throws ParameterInvalidException
     */
    public function run(): bool
    {
        if ($this->ids) {
            $ids = $this->ids;

            for ($i = 0; $i < count($ids); $i++) {
                $this->tool->destroy($ids[$i]);
            }

            Cache::tags(['catalog', 'tool'])->flush();
        }

        return true;
    }
}
