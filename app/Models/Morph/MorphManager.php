<?php
/**
 * Морфирование.
 * Пакет содержит классы для морфирования текста.
 *
 * @package App.Models.Morph
 */

namespace App\Models\Morph;

use Config;
use Illuminate\Support\Manager;

/**
 * Класс системы морфирования.
 */
class MorphManager extends Manager
{
    public function getDefaultDriver(): string
    {
        return Config::get('morph.driver');
    }
}
