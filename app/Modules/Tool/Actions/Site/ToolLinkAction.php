<?php
/**
 * Модуль Инструментов.
 * Этот модуль содержит все классы для работы с инструментами.
 *
 * @package App\Modules\Tool
 */

namespace App\Modules\Tool\Actions\Site;

use App\Modules\Course\Enums\Status;
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
class ToolLinkAction extends Action
{
    /**
     * ID категории.
     *
     * @var string|null
     */
    public string|null $link = null;

    /**
     * Метод запуска логики.
     *
     * @return ToolEntity|null Вернет результаты исполнения.
     * @throws ParameterInvalidException
     */
    public function run(): ?ToolEntity
    {
        $cacheKey = Util::getKey('tool', 'admin', 'get', $this->link);

        return Cache::tags(['catalog', 'tool'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $result = Tool::where('link', $this->link)
                    ->active()
                    ->whereHas('courses', function ($query) {
                        $query->where('status', Status::ACTIVE->value);
                    })
                    ->with([
                        'metatag',
                    ])->first();

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
