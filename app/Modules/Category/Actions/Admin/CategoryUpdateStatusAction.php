<?php
/**
 * Модуль Категорий.
 * Этот модуль содержит все классы для работы с категориями.
 *
 * @package App\Modules\Category
 */

namespace App\Modules\Category\Actions\Admin;

use App\Models\Action;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Category\Entities\Category as CategoryEntity;
use App\Modules\Category\Models\Category;
use Cache;

/**
 * Класс действия для обновления статуса категорий.
 */
class CategoryUpdateStatusAction extends Action
{
    /**
     * ID категории.
     *
     * @var int|string
     */
    private int|string $id;

    /**
     * Статус.
     *
     * @var bool
     */
    private bool $status;

    /**
     * @param int|string $id ID категории.
     * @param bool $status Статус.
     */
    public function __construct(int|string $id, bool $status)
    {
        $this->id = $id;
        $this->status = $status;
    }

    /**
     * Метод запуска логики.
     *
     * @return CategoryEntity Вернет результаты исполнения.
     * @throws RecordNotExistException
     */
    public function run(): CategoryEntity
    {
        $action = new CategoryGetAction($this->id);
        $categoryEntity = $action->run();

        if ($categoryEntity) {
            $categoryEntity->status = $this->status;

            $category = Category::find($this->id);
            $category->update($categoryEntity->toArray());

            Cache::tags(['catalog', 'category', 'direction', 'profession'])->flush();

            return $categoryEntity;
        }

        throw new RecordNotExistException(
            trans('category::actions.admin.categoryUpdateStatusAction.notExistCategory')
        );
    }
}
