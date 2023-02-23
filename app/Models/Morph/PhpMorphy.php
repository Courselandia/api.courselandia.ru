<?php
/**
 * Морфирование.
 * Пакет содержит классы для морфирования текста.
 *
 * @package App.Models.Morph
 */

namespace App\Models\Morph;

use App\Models\Contracts\Morph;
use Wamania\Snowball\NotFoundException;
use Wamania\Snowball\StemmerFactory;

/**
 * Класс драйвер морфирования на основе PhpMorphy.
 */
class PhpMorphy extends Morph
{
    /**
     * Метод для получения геообъекта.
     *
     * @param string|null $value Строка для морфирования.
     *
     * @return string|null Вернет отморфированный текст.
     * @throws NotFoundException
     */
    public function get(string $value = null): ?string
    {
        $stemmer = StemmerFactory::create('ru');
        $words = explode(' ', $value);

        for ($i = 0; $i < count($words); $i++) {
            $words[$i] = $stemmer->stem($words[$i]);
        }

        $value = implode(' ', $words);

        return mb_strtoupper($value);
    }
}
