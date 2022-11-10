<?php
/**
 * Морфирование.
 * Пакет содержит классы для морфирования текста.
 *
 * @package App.Models.Morph
 */

namespace App\Models\Morph;

use Morphy;
use App\Models\Contracts\Morph;

/**
 * Класс драйвер морфирования на основе PhpMorphy.
 */
class PhpMorphy extends Morph
{
    /**
     * Метод для получения геообъекта.
     *
     * @param  string|null  $value  Строка для морфирования.
     *
     * @return string|null Вернет отморфированный текст.
     */
    public function get(string $value = null): ?string
    {
        $result = Morphy::getPseudoRoot(mb_strtoupper($value));

        return $result[0] ?? null;
    }
}
