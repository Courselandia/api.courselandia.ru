<?php
/**
 * Статьи написанные искусственным интеллектом для разных сущностей.
 * Пакет содержит классы для хранения статей написанных искусственным интеллектом.
 *
 * @package App.Models.Article
 */

namespace App\Modules\Article\Filters;

use App\Modules\Article\Enums\Status;
use EloquentFilter\ModelFilter;

/**
 * Класс фильтр для таблицы написанных статей.
 */
class ArticleFilter extends ModelFilter
{
    /**
     * Поиск по ID.
     *
     * @param int|string $id ID.
     *
     * @return ArticleFilter Правила поиска.
     */
    public function id(int|string $id): ArticleFilter
    {
        return $this->where('articles.id', $id);
    }

    /**
     * Поиск по категории.
     *
     * @param string|array $category Категории.
     *
     * @return ArticleFilter Правила поиска.
     */
    public function category(string|array $category): ArticleFilter
    {
        return $this->whereIn('articles.category', is_array($category) ? $category : [$category]);
    }

    /**
     * Поиск по написанному тексту.
     *
     * @param string $query Строка поиска.
     *
     * @return ArticleFilter Правила поиска.
     */
    public function text(string $query): ArticleFilter
    {
        return $this->whereLike('articles.text', $query);
    }

    /**
     * Поиск по ID сущности.
     *
     * @param int|string $articleableId ID сущности.
     *
     * @return ArticleFilter Правила поиска.
     */
    public function articleable(int|string $articleableId): ArticleFilter
    {
        return $this->where('articles.articleable_id', $articleableId);
    }

    /**
     * Поиск по названию сущности.
     *
     * @param string $articleableType Название сущности.
     *
     * @return ArticleFilter Правила поиска.
     */
    public function articleableType(string $articleableType): ArticleFilter
    {
        return $this->where('articles.articleable_type', $articleableType);
    }

    /**
     * Поиск по категории.
     *
     * @param array|Status|string $statuses Статусы.
     *
     * @return ArticleFilter Правила поиска.
     */
    public function status(array|Status|string $statuses): ArticleFilter
    {
        return $this->whereIn('articles.status', is_array($statuses) ? $statuses : [$statuses]);
    }
}
