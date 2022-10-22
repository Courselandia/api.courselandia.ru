<?php
/**
 * Модуль Публикации.
 * Этот модуль содержит все классы для работы с публикациями.
 *
 * @package App\Modules\Publication
 */

namespace App\Modules\Publication\Pipes\Site\Read;

use App\Models\Rep\RepositoryCondition;
use App\Modules\Publication\Repositories\RepositoryQueryBuilderPublication;

/**
 * Класс пайплайн для формирования фильтра для публикации.
 */
class PublicationCondition
{
    /**
     * Получить фильтр.
     *
     * @param  int|null  $year  Год.
     * @param  string|null  $link  Ссылка на публикацию.
     * @param  int|string|null  $id  ID публикации.
     *
     * @return RepositoryQueryBuilderPublication Фильтры.
     */
    public static function get(int $year = null, string $link = null, int|string $id = null): RepositoryQueryBuilderPublication
    {
        $query = new RepositoryQueryBuilderPublication();

        if ($id) {
            $query->setId($id);
        }

        if ($link) {
            $query->addCondition(new RepositoryCondition('link', $link));
        }

        if ($year) {
            $query->setYear($year);
        }

        return $query;
    }
}
