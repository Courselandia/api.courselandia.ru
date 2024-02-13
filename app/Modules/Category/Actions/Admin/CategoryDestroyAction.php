<?php
/**
 * Модуль Категорий.
 * Этот модуль содержит все классы для работы с категориями.
 *
 * @package App\Modules\Category
 */

namespace App\Modules\Category\Actions\Admin;

use App\Models\Action;
use App\Modules\Category\Models\Category;
use Cache;

/**
 * Класс действия для удаления категории.
 */
class CategoryDestroyAction extends Action
{
    /**
     * Массив ID категорий.
     *
     * @var int[]|string[]
     */
    private array $ids;

    /**
     * @param array $ids Массив ID категорий.
     */
    public function __construct(array $ids)
    {
        $this->ids = $ids;
    }

    /**
     * Метод запуска логики.
     *
     * @return bool Вернет результаты исполнения.
     */
    public function run(): bool
    {
        if ($this->ids) {
            Category::destroy($this->ids);
            Cache::tags(['catalog', 'category', 'direction', 'profession'])->flush();
        }

        return true;
    }
}
