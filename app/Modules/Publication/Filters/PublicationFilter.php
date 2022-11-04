<?php
/**
 * Модуль Публикации.
 * Этот модуль содержит все классы для работы с публикациями.
 *
 * @package App\Modules\Publication
 */

namespace App\Modules\Publication\Filters;

use Config;
use Carbon\Carbon;
use EloquentFilter\ModelFilter;

/**
 * Класс фильтр для таблицы ролей пользователей.
 */
class PublicationFilter extends ModelFilter
{
    /**
     * Поиск по ID.
     *
     * @param int|string $id ID.
     *
     * @return PublicationFilter Правила поиска.
     */
    public function id(int|string $id): PublicationFilter
    {
        return $this->where('publications.id', $id);
    }

    /**
     * Поиск по дате статьи.
     *
     * @param array $dates Даты от и до.
     *
     * @return PublicationFilter Правила поиска.
     */
    public function publishedAt(array $dates): PublicationFilter
    {
        $dates = [
            Carbon::createFromFormat('Y-m-d O', $dates[0])->startOfDay()->setTimezone(Config::get('app.timezone')),
            Carbon::createFromFormat('Y-m-d O', $dates[1])->endOfDay()->setTimezone(Config::get('app.timezone')),
        ];

        return $this->whereBetween('publications.published_at', $dates);
    }

    /**
     * Поиск по заголовку статьи.
     *
     * @param string $query Строка поиска.
     *
     * @return PublicationFilter Правила поиска.
     */
    public function header(string $query): PublicationFilter
    {
        return $this->whereLike('publications.header', $query);
    }

    /**
     * Поиск по ссылке статьи.
     *
     * @param string $query Строка поиска.
     *
     * @return PublicationFilter Правила поиска.
     */
    public function link(string $query): PublicationFilter
    {
        return $this->whereLike('publications.link', $query);
    }

    /**
     * Поиск по анонсу статьи.
     *
     * @param string $query Строка поиска.
     *
     * @return PublicationFilter Правила поиска.
     */
    public function anons(string $query): PublicationFilter
    {
        return $this->whereLike('publications.anons', $query);
    }

    /**
     * Поиск по статье.
     *
     * @param string $query Строка поиска.
     *
     * @return PublicationFilter Правила поиска.
     */
    public function article(string $query): PublicationFilter
    {
        return $this->whereLike('publications.article', $query);
    }

    /**
     * Поиск по статусу.
     *
     * @param bool $status Статус.
     *
     * @return PublicationFilter Правила поиска.
     */
    public function status(bool $status): PublicationFilter
    {
        return $this->where('publications.status', $status);
    }
}
