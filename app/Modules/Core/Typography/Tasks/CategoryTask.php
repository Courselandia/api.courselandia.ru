<?php
/**
 * Модуль ядра системы.
 * Этот модуль содержит все классы для работы с ядром системы.
 *
 * @package App\Modules\Core
 */

namespace App\Modules\Core\Typography\Tasks;

use Typography;
use Illuminate\Database\Eloquent\Builder;
use App\Modules\Category\Models\Category;

/**
 * Типографирование категорий.
 */
class CategoryTask extends Task
{
    /**
     * Количество запускаемых заданий.
     *
     * @return int Количество.
     */
    public function count(): int
    {
        return $this->getQuery()->count();
    }

    /**
     * Запуск типографирования текстов.
     *
     * @return void
     */
    public function run(): void
    {
        $categories = $this
            ->getQuery()
            ->clone()
            ->get();

        foreach ($categories as $category) {
            $category->name = Typography::process($category->name, true);
            $category->header = Typography::process($category->header, true);
            $category->text = Typography::process($category->text);

            $category->save();

            $this->fireEvent('finished', [$category]);
        }
    }

    /**
     * Получить запрос на записи, которые нужно типографировать.
     *
     * @return Builder Построитель запроса.
     */
    private function getQuery(): Builder
    {
        return Category::orderBy('id', 'ASC');
    }
}
