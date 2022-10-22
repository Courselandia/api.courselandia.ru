<?php
/**
 * Модуль Документов.
 * Этот модуль содержит все классы для работы с документами которые хранятся к записям в базе данных.
 *
 * @package App\Modules\Document
 */

namespace App\Modules\Document\Models;

use Config;
use Illuminate\Support\Manager;

/**
 * Класс драйвер хранения записей об документах.
 */
class DocumentManager extends Manager
{
    /**
     * @see \Illuminate\Support\Manager::getDefaultDriver
     */
    public function getDefaultDriver(): string
    {
        return Config::get('document.record');
    }
}
