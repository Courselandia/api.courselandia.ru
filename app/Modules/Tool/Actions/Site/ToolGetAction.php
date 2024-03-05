<?php
/**
 * Модуль Инструментов.
 * Этот модуль содержит все классы для работы с инструментами.
 *
 * @package App\Modules\Tool
 */

namespace App\Modules\Tool\Actions\Site;

use Cache;
use Util;
use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Modules\Tool\Entities\Tool as ToolEntity;
use App\Modules\Tool\Models\Tool;

/**
 * Класс действия для получения категории.
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
        $cacheKey = Util::getKey('tool', 'admin', 'get', $this->id);

        return Cache::tags(['catalog', 'tool'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $result = Tool::with([
                    'metatag',
                ])->find($this->id);

                return $result ? ToolEntity::from($result->toArray()) : null;
            }
        );
    }
}
