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
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Tool\Entities\Tool as ToolEntity;
use App\Modules\Tool\Models\Tool;

/**
 * Класс действия для получения категории.
 */
class ToolGetAction extends Action
{
    /**
     * ID категории.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * Метод запуска логики.
     *
     * @return ToolEntity|null Вернет результаты исполнения.
     * @throws ParameterInvalidException
     */
    public function run(): ?ToolEntity
    {
        $cacheKey = Util::getKey('tool', 'admin', 'get', $this->id);

        return Cache::tags(['catalog', 'tool', 'category'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $result = Tool::with([
                    'metatag',
                    'categories',
                ])->find($this->id);

                if ($result) {
                    $item = $result->toArray();
                    $entity = new ToolEntity();
                    $entity->set($item);

                    return $entity;
                }

                return null;
            }
        );
    }
}
