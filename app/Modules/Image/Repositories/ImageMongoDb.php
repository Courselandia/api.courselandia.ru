<?php
/**
 * Модуль Изображения.
 * Этот модуль содержит все классы для работы с изображениями которые хранятся к записям в базе данных.
 *
 * @package App\Modules\Image
 */

namespace App\Modules\Image\Repositories;

use DB;
use Generator;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Image\Models\ImageMongoDb as ImageMongoDbModel;
use App\Modules\Image\Entities\Image as ImageEntity;

/**
 * Класс репозитория изображений на основе MongoDb.
 */
class ImageMongoDb extends Image
{
    /**
     * Создание.
     *
     * @param ImageEntity $entity Данные для добавления.
     *
     * @return int|string Вернет ID последней вставленной строки.
     * @throws ParameterInvalidException
     */
    public function create(ImageEntity $entity): int|string
    {
        /**
         * @var ImageMongoDbModel $model
         */
        $model = $this->newInstance();
        $pro = getImageSize($entity->path);

        $model->path = $entity->path;
        $model->cache = time();
        $model->folder = $this->getFolder();
        $model->width = $pro[0];
        $model->height = $pro[1];
        $model->format = $this->getFormatText($pro[2]);

        $model->save();

        return $model->id;
    }

    /**
     * Обновление.
     *
     * @param int|string $id Id записи для обновления.
     * @param ImageEntity $entity Данные для добавления.
     *
     * @return int|string Вернет ID вставленной строки.
     * @throws RecordNotExistException|ParameterInvalidException
     */
    public function update(int|string $id, ImageEntity $entity): int|string
    {
        /**
         * @var ImageMongoDbModel $model
         */
        $model = $this->newInstance()->newQuery()->find($id);

        if ($model) {
            $pro = getImageSize($entity->path);

            $model->path = $entity->path;
            $model->cache = time();
            $model->folder = $this->getFolder();
            $model->width = $pro[0];
            $model->height = $pro[1];
            $model->format = $this->getFormatText($pro[2]);

            $model->save();

            return $id;
        }

        throw new RecordNotExistException('The image #' . $id . ' does not exist.');
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
        return DB::connection('mongodb')
            ->table($this->newInstance()->getTable())
            ->where('_id', $id)
            ->update(['byte' => $byte]);
    }

    /**
     * Получить по первичному ключу.
     *
     * @param int|string $id Id записи.
     * @param ImageEntity|null $entity Сущность.
     *
     * @return ImageEntity|null Данные.
     * @throws ParameterInvalidException
     */
    public function get(int|string $id, ImageEntity $entity = null): ImageEntity|null {
        $image = $this->getById($id);

        if ($image) {
            $image->byte = null;

            return $image;
        }

        /**
         * @var ImageMongoDbModel $image
         */
        $image = $this->newInstance()->newQuery()->find($id);

        if ($image) {
            $entity = $entity ? clone $entity->set($image->toArray()) : clone $this->getEntity($image->toArray());

            $entity->id = $image->_id;
            $entity->path = $image->path;
            $entity->pathCache = $image->pathCache;
            $entity->pathSource = $image->pathSource;

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
     * @return string|null Вернет байт код изображения.
     * @throws ParameterInvalidException
     */
    public function getByte(int|string $id): ?string
    {
        $image = $this->getById($id);

        if ($image) {
            return $image->byte;
        }

        $image = DB::connection('mongodb')
            ->collection($this->newInstance()->getTable())
            ->where('id', $id)->first();

        return $image?->byte;
    }

    /**
     * Получение всех записей.
     *
     * @param ImageEntity|null $entity Сущность.
     *
     * @return Generator|ImageEntity|null Генератор.
     * @throws ParameterInvalidException
     */
    public function all(ImageEntity $entity = null): Generator|ImageEntity|null
    {
        $offset = -1;

        while (true) {
            $offset += 1;

            /**
             * @var ImageMongoDbModel $image
             */
            $image = $this->newInstance()
                ->newQuery()
                ->offset($offset)
                ->limit(1)
                ->first();

            if ($image) {
                $entity = $entity ? $entity->set($image->toArray()) : $this->getEntity($image->toArray());

                $entity->id = $image->_id;
                $entity->path = $image->path;
                $entity->pathCache = $image->pathCache;
                $entity->pathSource = $image->pathSource;
                $entity->byte = null;

                yield $entity;
            }

            break;
        }

        return null;
    }

    /**
     * Получить количество всех изображений.
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
