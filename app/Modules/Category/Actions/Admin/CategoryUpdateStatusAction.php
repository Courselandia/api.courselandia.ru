<?php
/**
 * Модуль Категорий.
 * Этот модуль содержит все классы для работы с категориями.
 *
 * @package App\Modules\Category
 */

namespace App\Modules\Category\Actions\Admin;

use App\Models\Action;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Category\Entities\Category as CategoryEntity;
use App\Modules\Category\Repositories\Category;
use Cache;
use ReflectionException;

/**
 * Класс действия для обновления статуса категорий.
 */
class CategoryUpdateStatusAction extends Action
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
     * Статус.
     *
     * @var bool|null
     */
    public ?bool $status = null;

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
     * @return CategoryEntity Вернет результаты исполнения.
     * @throws RecordNotExistException|ParameterInvalidException|ReflectionException
     */
    public function run(): CategoryEntity
    {
        $action = app(CategoryGetAction::class);
        $action->id = $this->id;
        $categoryEntity = $action->run();

        if ($categoryEntity) {
            $categoryEntity->status = $this->status;
            $this->category->update($this->id, $categoryEntity);
            Cache::tags(['catalog', 'category', 'direction', 'profession'])->flush();

            return $categoryEntity;
        }

        throw new RecordNotExistException(
            trans('category::actions.admin.categoryUpdateStatusAction.notExistCategory')
        );
    }
}
