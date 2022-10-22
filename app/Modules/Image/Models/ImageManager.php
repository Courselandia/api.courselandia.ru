<?php
/**
 * Модуль Изображения.
 * Этот модуль содержит все классы для работы с изображениями которые хранятся к записям в базе данных.
 *
 * @package App\Modules\Image
 */

namespace App\Modules\Image\Models;

use Config;
use Illuminate\Support\Manager;

/**
 * Класс драйвер хранения записей об изображениях.
 */
class ImageManager extends Manager
{
    /**
     * @see \Illuminate\Support\Manager::getDefaultDriver
     */
    public function getDefaultDriver(): string
    {
        return Config::get('image.record');
    }
}
