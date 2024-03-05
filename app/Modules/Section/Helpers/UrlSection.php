<?php
/**
 * Модуль Разделов.
 * Этот модуль содержит все классы для работы с разделами каталога.
 *
 * @package App\Modules\Section
 */

namespace App\Modules\Section\Helpers;

use Config;
use App\Modules\Section\Entities\Section as SectionEntity;

/**
 * Получение ссылки на раздел.
 */
class UrlSection
{
    /**
     * Получение ссылки на раздел.
     *
     * @param SectionEntity $entity Сущность раздела.
     * @return string|null URL раздела.
     */
    public static function get(SectionEntity $entity): string|null
    {
        if (isset($entity->items[0]->itemable['link'])) {
            $url = Config::get('app.url');
            $url .= '/courses/' . $entity->items[0]->type . '/' . $entity->items[0]->itemable['link'];

            if (isset($entity->items[1]->itemable['link'])) {
                $url .= '/' . $entity->items[1]->type . '/' . $entity->items[1]->itemable['link'];
            }

            if ($entity->level) {
                $url .= '/level/' . $entity->level->value;
            }

            if ($entity->free) {
                $url .= '/free';
            }

            return $url;
        }

        return null;
    }
}
