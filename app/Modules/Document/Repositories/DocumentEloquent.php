<?php
/**
 * Модуль Документов.
 * Этот модуль содержит все классы для работы с документами которые хранятся к записям в базе данных.
 *
 * @package App\Modules\Document
 */

namespace App\Modules\Document\Repositories;

use DB;
use Generator;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Document\Entities\Document as DocumentEntity;
use App\Modules\Document\Models\DocumentEloquent as DocumentEloquentModel;
use App\Models\Exceptions\ParameterInvalidException;

/**
 * Класс репозитория документов на основе Eloquent.
 */
class DocumentEloquent extends Document
{
    /**
     * Создание.
     *
     * @param DocumentEntity $entity Данные для добавления.
     *
     * @return int|string Вернет ID последней вставленной строки.
     * @throws ParameterInvalidException
     */
    public function create(DocumentEntity $entity): int|string
    {
        /**
         * @var DocumentEloquentModel $model
         */
        $model = $this->newInstance();

        $model->path = $entity->path;
        $model->cache = time();
        $model->folder = $this->getFolder();
        $model->format = pathinfo($entity->path)['extension'];

        $model->save();

        return $model->id;
    }

    /**
     * Обновление.
     *
     * @param int|string $id Id записи для обновления.
     * @param DocumentEntity $entity Данные для добавления.
     *
     * @return int|string Вернет ID вставленной строки.
     * @throws RecordNotExistException|ParameterInvalidException
     */
    public function update(int|string $id, DocumentEntity $entity): int|string
    {
        /**
         * @var DocumentEloquentModel $model
         */
        $model = $this->newInstance()->newQuery()->find($id);

        if ($model) {

            $model->path = $entity->path;
            $model->cache = time();
            $model->folder = $this->getFolder();
            $model->format = pathinfo($entity->path)['extension'];

            $model->save();

            return $id;
        }

        throw new RecordNotExistException('The document #' . $id . ' does not exist.');
    }

    /**
     * Обновление байт кода картинки.
     *
     * @param int|string $id Id записи для обновления.
     * @param string $byte Байт код картинки.
     *
     * @return bool Вернет булево значение успешности операции.
     * @throws ParameterInvalidException
     */
    public function updateByte(int|string $id, string $byte): bool
    {
        return DB::table($this->newInstance()->getTable())
            ->where('id', $id)
            ->update(['byte' => $byte]);
    }

    /**
     * Получить по первичному ключу.
     *
     * @param int|string $id Id записи.
     * @param DocumentEntity|null $entity Сущность.
     *
     * @return DocumentEntity|null Данные.
     * @throws ParameterInvalidException
     */
    public function get(int|string $id, DocumentEntity $entity = null): DocumentEntity|null
    {
        $document = $this->getById($id);

        if ($document) {
            $document->byte = null;

            return $document;
        }

        $query = $this->newInstance()->newQuery()->find($id);

        /**
         * @var DocumentEloquentModel $document
         */
        $document = $query->first();

        if ($document) {
            $entity = $entity ? $entity->set($document->toArray()) : $this->getEntity()->set($document->toArray());

            $entity->path = $document->path;
            $entity->pathCache = $document->pathCache;
            $entity->pathSource = $document->pathSource;

            $entity->byte = null;
            $this->setById($id, $entity);

            return $entity;
        }

        return null;
    }

    /**
     * Получение байт кода картинки.
     *
     * @param int|string $id Id записи для обновления.
     *
     * @return string|null Вернет байт код документа.
     * @throws ParameterInvalidException
     */
    public function getByte(int|string $id): ?string
    {
        $document = $this->getById($id);

        if ($document) {
            return $document->byte;
        }

        $document = DB::table($this->newInstance()->getTable())
            ->where('id', $id)
            ->first();

        return $document?->byte;
    }

    /**
     * Получение всех записей.
     *
     * @param DocumentEntity|null $entity Сущность.
     *
     * @return Generator|DocumentEntity|null Генератор.
     * @throws ParameterInvalidException
     */
    public function all(DocumentEntity $entity = null): Generator|DocumentEntity|null
    {
        $offset = -1;

        while (true) {
            $offset += 1;

            /**
             * @var DocumentEloquentModel $document
             */
            $document = $this->newInstance()
                ->newQuery()
                ->offset($offset)
                ->limit(1)
                ->first();

            if ($document) {
                $entity = $entity ? $entity->set($document->toArray()) : $this->getEntity()->set($document->toArray());

                $entity->path = $document->path;
                $entity->pathCache = $document->pathCache;
                $entity->pathSource = $document->pathSource;
                $entity->byte = null;

                yield $entity;
            }

            break;
        }

        return null;
    }

    /**
     * Получить количество всех документов.
     *
     * @return int Количество записей.
     * @throws ParameterInvalidException
     */
    public function count(): int
    {
        return $this->newInstance()->newQuery()->count();
    }

    /**
     * Удаление.
     *
     * @param int|string|array|null $id Id записи для удаления.
     *
     * @return bool Вернет булево значение успешности операции.
     * @throws ParameterInvalidException
     */
    public function destroy(int|string|array $id = null): bool
    {
        $model = $this->newInstance();

        return $model->destroy($id);
    }
}
