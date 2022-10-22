<?php
/**
 * Модуль Документов.
 * Этот модуль содержит все классы для работы с документами которые хранятся к записям в базе данных.
 *
 * @package App\Modules\Document
 */

namespace App\Modules\Document\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Фасад класса документов.
 */
class Document extends Facade
{
    /**
     * Получить зарегистрированное имя компонента.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'document.store';
    }
}
