<?php
/**
 * Модуль География.
 * Этот модуль содержит все классы для работы со странами, районами, городами и т.д.
 *
 * @package App\Modules\Location
 */

namespace App\Modules\Location\Values;

use App\Models\Value;

/**
 * Объект-значение для хранения стран.
 */
class Country extends Value
{
    /**
     * Код.
     *
     * @var string
     */
    public string $code;

    /**
     * Название.
     *
     * @var string
     */
    public string $name;

    /**
     * @param string $code Код.
     * @param string $name Название.
     */
    public function __construct(string $code, string $name)
    {
        $this->code = $code;
        $this->name = $name;
    }

    /**
     * Вернет код.
     *
     * @return string Код.
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * Вернет название.
     *
     * @return string Название.
     */
    public function getName(): string
    {
        return $this->name;
    }
}
