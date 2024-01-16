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
     * @var int|string
     */
    private int|string $id;

    /**
     * ID категории.
     *
     * @param int|string $id
     */
    public function __construct(int|string $id)
    {
        $this->id = $id;
    }

    /**
     * Метод запуска логики.
     *
     * @return CategoryEntity|null Вернет результаты исполнения.
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
                    'analyzers',
                ])->find($this->id);

                if ($result) {
                    print_r($result->toArray());
                    return CategoryEntity::from($result->toArray());
                }

                return null;
            }
        );
    }
}
