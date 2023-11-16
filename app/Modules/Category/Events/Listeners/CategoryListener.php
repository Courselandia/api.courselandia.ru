<?php
/**
 * Модуль Категорий.
 * Этот модуль содержит все классы для работы с категориями.
 *
 * @package App\Modules\Category
 */

namespace App\Modules\Category\Events\Listeners;

use App\Modules\Category\Models\Category;

/**
 * Класс обработчик событий для модели категорий.
 */
class CategoryListener
{
    /**
     * Обработчик события при удалении записи.
     *
     * @param  Category  $category  Модель для таблицы категорий.
     *
     * @return bool Вернет успешность выполнения операции.
     */
    public function deleting(Category $category): bool
    {
        $category->deleteRelation($category->metatag(), $category->isForceDeleting());
        $category->directions()->detach();
        $category->professions()->detach();
        $category->courses()->detach();
        $category->deleteRelation($category->analyzers(), $category->isForceDeleting());
        $category->deleteRelation($category->articles(), $category->isForceDeleting());

        return true;
    }
}
