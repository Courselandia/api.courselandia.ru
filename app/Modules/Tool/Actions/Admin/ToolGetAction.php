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
     * @var int|string
     */
    private int|string $id;

    /**
     * @param int|string $id ID инструмента.
     */
    public function __construct(int|string $id)
    {
        $this->id = $id;
    }

    /**
     * Метод запуска логики.
     *
     * @return ToolEntity|null Вернет результаты исполнения.
     */
    public function run(): ?ToolEntity
    {
        $cacheKey = Util::getKey('tool', $this->id, 'metatag');

        return Cache::tags(['catalog', 'tool'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $tool = Tool::where('id', $this->id)
                    ->with([
                        'metatag',
                        'analyzers',
                    ])
                    ->first();

                return $tool ? ToolEntity::from($tool->toArray()) : null;
            }
        );
    }
}
