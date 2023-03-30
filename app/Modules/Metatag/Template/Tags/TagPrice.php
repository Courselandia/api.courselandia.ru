<?php
/**
 * Модуль Метатэги.
 * Этот модуль содержит все классы для работы с метатегами.
 *
 * @package App\Modules\Metatag
 */

namespace App\Modules\Metatag\Template\Tags;

use Util;
use App\Modules\Course\Enums\Currency;
use App\Modules\Metatag\Template\Tag;

/**
 * Тэг обработчик для стоимости курса: {price}.
 */
class TagPrice extends Tag
{
    /**
     * Название тэга.
     *
     * @return string
     */
    public function getName(): string
    {
        return 'price';
    }

    /**
     * Конвертирование тэга в значение.
     *
     * @param string|null $value Значения для тэга.
     * @param array<string>|null $configs Настройки тэга.
     * @param array<string, string>|null $values Значения для шаблона.
     *
     * @return string|null
     */
    public function convert(?string $value = null, ?array $configs = null, ?array $values = null): string|null
    {
        /**
         * @var Currency $currency
         */
        $currency = $values['currency'] ?? Currency::RUB;

        if ($currency === Currency::RUB) {
            $currency = 'руб.';
        } else if ($currency === Currency::USD) {
            $currency = '$';
        } else if ($currency === Currency::EUR) {
            $currency = '€';
        }

        return Util::getMoney($value, false, $currency, false);
    }
}
