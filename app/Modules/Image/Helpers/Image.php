<?php
/**
 * Модуль Изображения.
 * Этот модуль содержит все классы для работы с изображениями которые хранятся к записям в базе данных.
 *
 * @package App\Modules\Image
 */

namespace App\Modules\Image\Helpers;

use Illuminate\Http\UploadedFile;
use App\Modules\Image\Entities\Image as ImageEntity;

/**
 * Вспомогательный класс.
 */
class Image
{
    /**
     * Запись изображения в таблицу базы данных.
     *
     * @param  string  $name  Название атрибута.
     * @param  array|int|string|UploadedFile|null  $value  Значение атрибута.
     * @param  Callable  $callback  Метод обработки изображения.
     *
     * @return null|int|string Вернет ID записи изображения.
     */
    public static function set(string $name, array|int|string|UploadedFile|ImageEntity|null $value, callable $callback): null|int|string
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
        } elseif ($value instanceof ImageEntity) {
            return $value->id;
        }

        return null;
    }
}
