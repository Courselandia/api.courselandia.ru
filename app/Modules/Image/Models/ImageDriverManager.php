<?php
/**
 * Модуль Изображения.
 * Этот модуль содержит все классы для работы с изображениями которые хранятся к записям в базе данных.
 *
 * @package App\Modules\Image
 */

namespace App\Modules\Image\Models;

use Illuminate\Support\Manager;
use Config;

/**
 * Класс драйвер хранения изображений.
 */
class ImageDriverManager extends Manager
{
    /**
     * @see \Illuminate\Support\Manager::getDefaultDriver
     */
    public function getDefaultDriver(): string
    {
        return Config::get('image.store_driver');
    }
}
