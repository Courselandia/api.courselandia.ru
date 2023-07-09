<?php
/**
 * Модуль ядра системы.
 * Этот модуль содержит все классы для работы с ядром системы.
 *
 * @package App\Modules\Core
 */

namespace App\Modules\Core\Typography\Tasks;

use App\Models\Error;
use App\Models\Event;
use EMT\EMTypograph;

/**
 * Абстрактный класс для написания собственных заданий на типографирования текстов.
 */
abstract class Task
{
    use Error;
    use Event;

    /**
     * Количество запускаемых заданий.
     *
     * @return int Количество.
     */
    abstract public function count(): int;

    /**
     * Запуск типографирования текстов.
     *
     * @return void
     */
    abstract public function run(): void;

    /**
     * Типографирование текста.
     *
     * @param ?string $text Текст для тпиографирования.
     * @param bool $htmlEntityDecode Преобразует HTML-сущности в соответствующие им символы.
     *
     * @return ?string Оттипографированный текст.
     */
    protected function typography(?string $text, bool $htmlEntityDecode = false): ?string
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

                $result = $typograph->process($value);

                if ($result) {
                    return $htmlEntityDecode ? html_entity_decode($result) : $result;
                }
            }
        }

        return $text;
    }
}
