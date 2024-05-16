<?php
/**
 * Модуль Документов.
 * Этот модуль содержит все классы для работы с документами которые хранятся к записям в базе данных.
 *
 * @package App\Modules\Document
 */

namespace App\Modules\Document\Repositories;

use DB;
use Cache;
use Util;
use Generator;
use App\Models\Enums\CacheTime;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Document\Models\DocumentMongoDb as DocumentMongoDbModel;
use App\Modules\Document\Entities\Document as DocumentEntity;

/**
 * Класс репозитория документов на основе MongoDb.
 */
class DocumentMongoDb extends Document
{
    /**
     * Создание.
     *
     * @param DocumentEntity $entity Данные для добавления.
     *
     * @return int|string Вернет ID последней вставленной строки.
     */
    public function create(DocumentEntity $entity): int|string
    {
        /**
         * @var DocumentMongoDbModel $model
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
     * @throws RecordNotExistException
     */
    public function update(int|string $id, DocumentEntity $entity): int|string
    {
        $cacheKey = Util::getKey('document', 'mongodb', $id);

        /**
         * @var DocumentMongoDbModel|null $model
         */
        $model = Cache::tags(['document'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($id) {
                return $this->newInstance()->newQuery()->find($id);
            }
        );

        if ($model) {
            $model->path = $entity->path;
            $model->cache = time();
            $model->folder = $this->getFolder();
            $model->format = pathinfo($entity->path)['extension'];

            $model->save();

            Cache::tags(['document'])->forget($cacheKey);

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
     */
    public function updateByte(int|string $id, string $byte): bool
    {
        $cacheKey = Util::getKey('document', 'mongodb', $id);
        Cache::tags(['document'])->forget($cacheKey);

        return DB::connection('mongodb')
            ->table($this->newInstance()->getTable())
            ->where('_id', $id)
            ->update(['byte' => $byte]);
    }

    /**
     * Получить по первичному ключу.
     *
     * @param int|string $id Id записи.
     * @param DocumentEntity|null $entity Сущность.
     *
     * @return DocumentEntity|null Данные.
     */
    public function get(int|string $id, DocumentEntity $entity = null): DocumentEntity|null
    {
        $document = $this->getById($id);

        if ($document) {
            $document->byte = null;

            return $document;
        }

        $cacheKey = Util::getKey('document', 'mongodb', $id);

        /**
         * @var DocumentMongoDbModel|null $document
         */
        $document = Cache::tags(['document'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($id) {
                return $this->newInstance()->newQuery()->find($id);
            }
        );

        if ($document) {
            $entity = $entity ? $entity->set($document->toArray()) : $this->getEntity($document->toArray());

            $entity->id = $document->_id;
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
     */
    public function getByte(int|string $id): ?string
    {
        $document = $this->getById($id);

        if ($document) {
            return $document->byte;
        }

        $cacheKey = Util::getKey('document', 'mongodb', $id);

        /**
         * @var DocumentMongoDbModel|null $document
         */
        $document = Cache::tags(['document'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($id) {
                return DB::connection('mongodb')
                    ->collection($this->newInstance()->getTable())
                    ->where('id', $id)
                    ->first();
            }
        );

        return $document?->byte;
    }

    /**
     * Получение всех записей.
     *
     * @param DocumentEntity|null $entity Сущность.
     *
     * @return Generator|DocumentEntity|null Генератор.
     */
    public function all(DocumentEntity $entity = null): Generator|DocumentEntity|null
    {
        $offset = -1;

        while (true) {
            $offset += 1;

            /**
             * @var DocumentMongoDbModel $document
             */
            $document = $this->newInstance()
                ->newQuery()
                ->offset($offset)
                ->limit(1)
                ->first();

            if ($document) {
                $entity = $entity ? $entity->set($document->toArray()) : $this->getEntity($document->toArray());

                $entity->id = $document->_id;
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
     */
    public function destroy(int|string|array $id = null): bool
    {
        if (is_array($id)) {
            foreach ($id as $itm) {
                $cacheKey = Util::getKey('document', 'mongodb', $itm);
                Cache::tags(['document'])->forget($cacheKey);
            }
        } else {
            $cacheKey = Util::getKey('document', 'mongodb', $id);
            Cache::tags(['document'])->forget($cacheKey);
        }

        $model = $this->newInstance();

        return $model->destroy($id);
    }
}
