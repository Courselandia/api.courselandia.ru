<?php
/**
 * Модуль Метатэги.
 * Этот модуль содержит все классы для работы с метатегами.
 *
 * @package App\Modules\Metatag
 */

namespace App\Modules\Metatag\Template;

/**
 * Абстрактный тэг для создания собственного тэга.
 * Будет служить обработчиком тэга.
 */
abstract class Tag
{
    /**
     * Название тэга.
     *
     * @return string
     */
    abstract public function getName(): string;

    /**
     * Конвертирование тэга в значение.
     *
     * @param string|null $value Значения для тэга.
     * @param array<string>|null $configs Настройки тэга.
     * @param array<string, string>|null $values Значение для шаблона.
     *
     * @return string|null
     */
    abstract public function convert(?string $value = null, ?array $configs = null, ?array $values = null): ?string;
}
