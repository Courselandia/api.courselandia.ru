<?php
/**
 * Модуль Документов.
 * Этот модуль содержит все классы для работы с документами которые хранятся к записям в базе данных.
 *
 * @package App\Modules\Document
 */

namespace App\Modules\Document\Models;

use Illuminate\Support\Manager;
use Config;

/**
 * Класс драйвер хранения документов.
 */
class DocumentDriverManager extends Manager
{
    /**
     * @see \Illuminate\Support\Manager::getDefaultDriver
     */
    public function getDefaultDriver(): string
    {
        return Config::get('document.store_driver');
    }
}
