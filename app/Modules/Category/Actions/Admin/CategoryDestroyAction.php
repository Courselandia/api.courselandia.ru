<?php
/**
 * Модуль Категорий.
 * Этот модуль содержит все классы для работы с категориями.
 *
 * @package App\Modules\Category
 */

namespace App\Modules\Category\Actions\Admin;

use App\Models\Action;
use App\Modules\Category\Repositories\Category;
use Cache;

/**
 * Класс действия для удаления категории.
 */
class CategoryDestroyAction extends Action
{
    /**
     * Репозиторий категорий.
     *
     * @var Category
     */
    private Category $category;

    /**
     * Массив ID пользователей.
     *
     * @var int[]|string[]
     */
    public ?array $ids = null;

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
     * @return bool Вернет результаты исполнения.
     */
    public function run(): bool
    {
        if ($this->ids) {
            $ids = $this->ids;

            for ($i = 0; $i < count($ids); $i++) {
                $this->category->destroy($ids[$i]);
            }

            Cache::tags(['catalog', 'category', 'direction', 'profession'])->flush();
        }

        return true;
    }
}
