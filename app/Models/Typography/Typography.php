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
     * @param bool $removeRn Удалить переводы строк.
     *
     * @return ?string Оттипографированный текст.
     */
    public function process(?string $text, bool $htmlEntityDecode = false, bool $removeRn = true): string | null
    {
        if ($text) {
            if ($removeRn) {
                $text = str_replace("\t", '', $text);
                $text = str_replace("\n\r", '', $text);
                $text = str_replace("\r\n", '', $text);
                $text = str_replace("\n", '', $text);
                $text = str_replace("\r", '', $text);
            }

            if ($text !== '') {
                $typograph = new EMTypograph();

                $typograph->do_setup('OptAlign.all', false);
                $typograph->do_setup('Text.paragraphs', false);
                $typograph->do_setup('Text.breakline', false);
                $typograph->do_setup('Nobr.all', false);
                $typograph->do_setup('Abbr.all', false);
                $typograph->do_setup('Nobr.spaces_nobr_in_surname_abbr', false);
                $typograph->do_setup('Number.math_chars', false);
                $typograph->do_setup('Abbr.nobr_before_unit_volt', false);
                $typograph->do_setup('Space.autospace_after', false);

                $result = $typograph->process($text);

                if ($result) {
                    return $htmlEntityDecode ? html_entity_decode(strip_tags($result)) : $result;
                }
            }
        }

        return $text;
    }
}
