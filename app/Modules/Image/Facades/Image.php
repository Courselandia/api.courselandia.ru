<?php
/**
 * Модуль Изображения.
 * Этот модуль содержит все классы для работы с изображениями которые хранятся к записям в базе данных.
 *
 * @package App\Modules\Image
 */

namespace App\Modules\Image\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Фасад класса изображений.
 */
class Image extends Facade
{
    /**
     * Получить зарегистрированное имя компонента.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'image.store';
    }
}
