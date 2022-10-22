<?php
/**
 * Модуль География.
 * Этот модуль содержит все классы для работы со странами, районами, городами и т.д.
 *
 * @package App\Modules\Location
 */

namespace App\Modules\Location\Entities;

use App\Models\Entity;

/**
 * Сущность для хранения стран.
 */
class Country extends Entity
{
    /**
     * Код.
     *
     * @var string|null
     */
    public ?string $code = null;

    /**
     * Название.
     *
     * @var string|null
     */
    public ?string $name = null;
}