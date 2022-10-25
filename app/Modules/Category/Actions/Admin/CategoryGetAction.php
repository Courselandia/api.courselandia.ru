<?php
/**
 * Модуль Категорий.
 * Этот модуль содержит все классы для работы с категориями.
 *
 * @package App\Modules\Category
 */

namespace App\Modules\Category\Actions\Admin;

use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Rep\RepositoryQueryBuilder;
use App\Modules\Category\Entities\Category as CategoryEntity;
use App\Modules\Category\Repositories\Category;
use Cache;
use ReflectionException;
use Util;

/**
 * Класс действия для получения категории.
 */
class CategoryGetAction extends Action
{
    /**
     * Репозиторий категорий.
     *
     * @var Category
     */
    private Category $category;

    /**
     * ID категории.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * Конструктор.
     *
     * @param  Category  $category  Репозиторий категорий.
     */
    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    /**
     * Метод запуска логики.
     *
     * @return CategoryEntity|null Вернет результаты исполнения.
     * @throws ParameterInvalidException|ReflectionException
     */
    public function run(): ?CategoryEntity
    {
        $query = new RepositoryQueryBuilder();
        $query->setId($this->id)
            ->setRelations([
                'metatag',
                'directions',
                'professions',
            ]);

        $cacheKey = Util::getKey('category', $query);

        return Cache::tags(['catalog', 'category', 'direction', 'profession'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($query) {
                return $this->category->get($query);
            }
        );
    }
}
