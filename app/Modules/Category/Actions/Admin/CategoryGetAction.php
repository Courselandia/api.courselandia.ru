<?php
/**
 * Модуль Категорий.
 * Этот модуль содержит все классы для работы с категориями.
 *
 * @package App\Modules\Category
 */

namespace App\Modules\Category\Actions\Admin;

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
class CategoryGetAction extends Action
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
     * @return CategoryEntity|null Вернет результаты исполнения.
     * @throws ParameterInvalidException
     */
    public function run(): ?CategoryEntity
    {
        $cacheKey = Util::getKey('category', 'admin', 'get', $this->id);

        return Cache::tags(['catalog', 'category', 'direction', 'profession'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $result = Category::with([
                    'metatag',
                    'directions',
                    'professions',
                ])->find($this->id);

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
