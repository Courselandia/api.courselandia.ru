<?php
/**
 * Модуль Категорий.
 * Этот модуль содержит все классы для работы с категориями.
 *
 * @package App\Modules\Category
 */

namespace App\Modules\Category\Actions\Site;

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
     * @param int|string $id ID категории.
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

        return Cache::tags(['catalog', 'category'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $result = Category::with([
                    'metatag',
                    'directions',
                    'professions',
                ])->find($this->id);

                if ($result) {
                    return CategoryEntity::from($result->toArray());
                }

                return null;
            }
        );
    }
}
