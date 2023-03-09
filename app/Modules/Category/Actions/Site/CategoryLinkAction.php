<?php
/**
 * Модуль Категорий.
 * Этот модуль содержит все классы для работы с категориями.
 *
 * @package App\Modules\Category
 */

namespace App\Modules\Category\Actions\Site;

use App\Modules\Course\Enums\Status;
use Cache;
use Util;
use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Category\Entities\Category as CategoryEntity;
use App\Modules\Category\Models\Category;

/**
 * Класс действия для получения категории.
 */
class CategoryLinkAction extends Action
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
     * @return CategoryEntity|null Вернет результаты исполнения.
     * @throws ParameterInvalidException
     */
    public function run(): ?CategoryEntity
    {
        $cacheKey = Util::getKey('category', 'admin', 'get', $this->link);

        return Cache::tags(['catalog', 'category', 'direction', 'profession'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $result = Category::where('link', $this->link)
                    ->whereHas('courses', function ($query) {
                        $query->where('status', Status::ACTIVE->value);
                    })
                    ->with([
                        'metatag',
                        'directions',
                        'professions',
                    ])->first();

                if ($result) {
                    $item = $result->toArray();
                    $entity = new CategoryEntity();
                    $entity->set($item);

                    return $entity;
                }

                return null;
            }
        );
    }
}
