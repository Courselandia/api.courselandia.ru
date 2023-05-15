<?php
/**
 * Статьи написанные искусственным интеллектом для разных сущностей.
 * Пакет содержит классы для хранения статей написанных искусственным интеллектом.
 *
 * @package App.Models.Article
 */

namespace App\Modules\Article\Categories;

use Illuminate\Support\Manager;

/**
 * Класс менеджер для принятия текстов на основе категорий.
 */
class ArticleCategoryManager extends Manager
{
    public function getDefaultDriver(): string
    {
        return 'course.text';
    }
}
