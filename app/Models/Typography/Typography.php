<?php
/**
 * Типографирование.
 * Пакет содержит классы для типографирования текста.
 *
 * @package App.Models.Morph
 */

namespace App\Models\Typography;

use EMT\EMTypograph;

/**
 * Типографирование текста.
 */
class Typography
{
    /**
     * Типографирование текста.
     *
     * @param ?string $text Текст для тпиографирования.
     * @param bool $htmlEntityDecode Преобразует HTML-сущности в соответствующие им символы.
     *
     * @return ?string Оттипографированный текст.
     */
    public function process(?string $text, bool $htmlEntityDecode = false): string | null
    {
        if ($text) {
            $value = str_replace("\t", '', $text);
            $value = str_replace("\n\r", '', $value);
            $value = str_replace("\r\n", '', $value);
            $value = str_replace("\n", '', $value);
            $value = str_replace("\r", '', $value);

            if ($value !== '') {
                $typograph = new EMTypograph();

                $typograph->do_setup('OptAlign.all', false);
                $typograph->do_setup('Text.paragraphs', false);
                $typograph->do_setup('Text.breakline', false);
                $typograph->do_setup('Nobr.all', false);
                $typograph->do_setup('Abbr.all', false);
                $typograph->do_setup('Nobr.spaces_nobr_in_surname_abbr', false);
                $typograph->do_setup('Number.math_chars', false);

                $result = $typograph->process($value);

                if ($result) {
                    return $htmlEntityDecode ? html_entity_decode(strip_tags($result)) : $result;
                }
            }
        }

        return $text;
    }
}
