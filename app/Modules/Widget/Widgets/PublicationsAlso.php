<?php
/**
 * Модуль Виджетов.
 * Этот модуль содержит все классы для работы с виджетами, которые можно использовать в публикациях.
 *
 * @package App\Modules\Widget
 */

namespace App\Modules\Widget\Widgets;

use Cache;
use Util;
use App\Models\Enums\CacheTime;
use App\Modules\Widget\Contracts\Widget;
use App\Modules\Publication\Models\Publication;
use App\Modules\Publication\Entities\Publication as PublicationEntity;

/**
 * Виджет: Публикации - Читайте так же
 */
class PublicationsAlso implements Widget
{
    /**
     * Рендеринг виджета.
     *
     * @param array $values Значения виджета (указываются в настройках).
     * @param array $params Параметры виджета (указываются в тэге).
     *
     * @return string|null Вернет готовый HTML виджета.
     */
    public function render(array $values, array $params): ?string
    {
        if (!isset($params['id']) || !$params['id']) {
            return null;
        }

        $id = $params['id'];

        $cacheKey = Util::getKey(
            'publication',
            'site',
            'widget',
            $id,
        );

        return Cache::tags(['publication'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($id, $values, $params) {
                $publication = Publication::find($params['id']);

                if (!$publication) {
                    return null;
                }

                return view('widget::widgets.publicationsAlso', [
                    'publication' => PublicationEntity::from($publication->toArray()),
                    'values' => $values,
                    'params' => $params,
                ])->render();
            }
        );
    }
}