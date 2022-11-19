<?php
/**
 * Модуль Инструментов.
 * Этот модуль содержит все классы для работы с инструментами.
 *
 * @package App\Modules\Tool
 */

namespace App\Modules\Tool\Actions\Admin;

use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Tool\Entities\Tool as ToolEntity;
use App\Modules\Tool\Models\Tool;
use Cache;
use Util;

/**
 * Класс действия для получения инструмента.
 */
class ToolGetAction extends Action
{
    /**
     * ID инструмента.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

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
     * @return ToolEntity|null Вернет результаты исполнения.
     * @throws ParameterInvalidException
     */
    public function run(): ?ToolEntity
    {
        $cacheKey = Util::getKey('tool', $this->id, 'metatag');

        return Cache::tags(['catalog', 'tool'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $tool = Tool::where('id', $this->id)
                    ->with('metatag')
                    ->first();

                return $tool ? new ToolEntity($tool->toArray()) : null;
            }
        );
    }
}
