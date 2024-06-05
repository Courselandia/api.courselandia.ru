<?php
/**
 * Модуль Виджетов.
 * Этот модуль содержит все классы для работы с виджетами, которые можно использовать в публикациях.
 *
 * @package App\Modules\Widget
 */

namespace App\Modules\Widget\Widgets;

use App\Modules\Widget\Contracts\Widget;

/**
 * Виджет: Публикации - Читайте так же
 */
class PublicationsAlso implements Widget
{
    /**
     * Рендеринг виджета.
     *
     * @param array $values Значения виджета.
     * @param array $params Параметры виджета.
     *
     * @return string|null Вернет готовый HTML виджета.
     */
    public function render(array $values, array $params): ?string
    {
        return 'Publications Also';
    }
}