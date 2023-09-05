<?php
/**
 * Модуль Документов.
 * Этот модуль содержит все классы для работы с документами которые хранятся к записям в базе данных.
 *
 * @package App\Modules\Document
 */

namespace App\Modules\Document\Events\Listeners;

use Eloquent;
use App;
use Config;

/**
 * Класс обработчик событий для модели документов.
 */
class DocumentListener
{
    /**
     * Обработчик события при добавлении записи.
     *
     * @param  Eloquent  $document  Модель документов.
     *
     * @return bool Вернет успешность выполнения операции.
     */
    public function created(Eloquent $document): bool
    {
        return App::make('document.store.driver')->create(
            $document->folder,
            $document->id,
            $document->format,
            $document->path
        );
    }

    /**
     * Обработчик события при обновлении записи.
     *
     * @param  Eloquent  $document  Модель документов.
     *
     * @return bool Вернет успешность выполнения операции.
     */
    public function updated(Eloquent $document): bool
    {
        $original = $document->getRawOriginal();
        $id = $original['_id'] ?? $original['id'];
        App::make('document.store.driver')->destroy($original['folder'], $id, $original['format']);

        return App::make('document.store.driver')->update(
            $document->folder,
            $document->id,
            $document->format,
            $document->path
        );
    }

    /**
     * Обработчик события при чтении данных.
     *
     * @param  Eloquent  $document  Модель документов.
     *
     * @return bool Вернет успешность выполнения операции.
     */
    public function readed(Eloquent $document): bool
    {
        $document->path = Config::get('app.api_url') . '/' . App::make('document.store.driver')->path(
                $document->folder,
                $document->id,
                $document->format
            );
        $document->pathCache = $document->path;
        $document->byte = App::make('document.store.driver')->read($document->folder, $document->id, $document->format);

        if ($document->cache) {
            $document->path .= '?'.$document->cache;
        }

        $document->pathSource = App::make('document.store.driver')->pathSource(
            $document->folder,
            $document->id,
            $document->format
        );

        return true;
    }

    /**
     * Обработчик события при удалении записи.
     *
     * @param  Eloquent  $document  Модель документов.
     *
     * @return bool Вернет успешность выполнения операции.
     */
    public function deleted(Eloquent $document): bool
    {
        if (!Config::get('document.soft_deletes')) {
            return App::make('document.store.driver')->destroy($document->folder, $document->id, $document->format);
        }

        return true;
    }
}
