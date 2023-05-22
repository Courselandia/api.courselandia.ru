<?php
/**
 * Искусственный интеллект писатель.
 * Пакет содержит классы для написания текстов с использованием искусственного интеллекта.
 *
 * @package App.Models.Writer
 */

namespace App\Modules\Writer\Models;

use Config;
use Illuminate\Support\Manager;

/**
 * Класс системы написание текстов.
 */
class WriterManager extends Manager
{
    public function getDefaultDriver(): string
    {
        return Config::get('writer.driver');
    }
}
