<?php
/**
 * Модуль Документов.
 * Этот модуль содержит все классы для работы с документами которые хранятся к записям в базе данных.
 *
 * @package App\Modules\Document
 */

namespace App\Modules\Document\Helpers;

use Illuminate\Http\UploadedFile;

/**
 * Вспомогательный класс.
 */
class Document
{
    /**
     * Запись документа в таблицу базы данных.
     *
     * @param  string  $name  Название атрибута.
     * @param  array|int|string|UploadedFile|null  $value  Значение атрибута.
     * @param  Callable  $callback  Метод обработки документа.
     *
     * @return null|int|string Вернет ID записи документа.
     */
    public static function set(string $name, array|int|string|UploadedFile|null $value, callable $callback): null|int|string
    {
        if (!$value) {
            return null;
        }

        if (is_array($value)) {
            return $value['id'];
        } elseif (is_numeric($value) || is_string($value)) {
            return $value;
        } elseif ($value instanceof UploadedFile) {
            return $callback($name, $value);
        }

        return null;
    }
}
