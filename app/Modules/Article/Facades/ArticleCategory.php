<?php
/**
 * Статьи написанные искусственным интеллектом для разных сущностей.
 * Пакет содержит классы для хранения статей написанных искусственным интеллектом.
 *
 * @package App.Models.Article
 */

namespace App\Modules\Article\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Фасад класса для написания текстов искусственным интеллектом.
 */
class ArticleCategory extends Facade
{
    /**
     * Получить зарегистрированное имя компонента.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'articleCategory';
    }
}
