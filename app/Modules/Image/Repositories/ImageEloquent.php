<?php
/**
 * Модуль Изображения.
 * Этот модуль содержит все классы для работы с изображениями которые хранятся к записям в базе данных.
 *
 * @package App\Modules\Image
 */

namespace App\Modules\Image\Repositories;

use DB;
use Cache;
use Util;
use Generator;
use App\Models\Enums\CacheTime;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Image\Entities\Image as ImageEntity;
use App\Modules\Image\Models\ImageEloquent as ImageEloquentModel;

/**
 * Класс репозитория изображений на основе Eloquent.
 */
class ImageEloquent extends Image
{
    /**
     * Создание.
     *
     * @param ImageEntity $entity Данные для добавления.
     *
     * @return int|string Вернет ID последней вставленной строки.
     */
    public function create(ImageEntity $entity): int|string
    {
        /**
         * @var ImageEloquentModel $model
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
     * @throws RecordNotExistException
     */
    public function update(int|string $id, ImageEntity $entity): int|string
    {
        $cacheKey = Util::getKey('image', 'mysql', $id);

        /**
         * @var ImageEloquentModel|null $model
         */
        $model = Cache::tags(['image'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($id) {
                return $this->newInstance()->newQuery()->find($id);
            }
        );

        if ($model) {
            $pro = getImageSize($entity->path);

            $model->path = $entity->path;
            $model->cache = time();
            $model->folder = $this->getFolder();
            $model->width = $pro[0];
            $model->height = $pro[1];
            $model->format = $this->getFormatText($pro[2]);

            $model->save();

            Cache::tags(['image'])->forget($cacheKey);

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
     */
    public function updateByte(int|string $id, string $byte): bool
    {
        $cacheKey = Util::getKey('image', 'mysql', $id);
        Cache::tags(['image'])->forget($cacheKey);

        return DB::table($this->newInstance()->getTable())
            ->where('id', $id)
            ->update(['byte' => $byte]);
    }

    /**
     * Получить по первичному ключу.
     *
     * @param int|string $id Id записи.
     *
     * @return ImageEntity|null Данные.
     */
    public function get(int|string $id): ImageEntity|null {
        $image = $this->getById($id);

        if ($image) {
            $image->byte = null;

            return $image;
        }

        $cacheKey = Util::getKey('image', 'mysql', $id);

        /**
         * @var ImageEloquentModel|null $image
         */
        $image = Cache::tags(['image'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($id) {
                return $this->newInstance()->newQuery()->find($id);
            }
        );

        if ($image) {
            /**
             * @var ImageEntity $entity
             */
            $entity = $this->getEntity([
                ...$image->toArray(),
                'path' => $image->path,
                'pathCache' => $image->pathCache,
                'pathSource' => $image->pathSource,
                'byte' => null,
            ]);

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
     */
    public function getByte(int|string $id): ?string
    {
        $image = $this->getById($id);

        if ($image) {
            return $image->byte;
        }

        $cacheKey = Util::getKey('image', 'mysql', $id);

        /**
         * @var ImageEloquentModel|null $image
         */
        $image = Cache::tags(['image'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($id) {
                return DB::table($this->newInstance()->getTable())
                    ->where('id', $id)
                    ->first();
            }
        );

        return $image?->byte;
    }

    /**
     * Получение всех записей.
     *
     * @param ImageEntity|null $entity Сущность.
     *
     * @return Generator|ImageEntity|null Генератор.
     */
    public function all(ImageEntity $entity = null): Generator|ImageEntity|null
    {
        $offset = -1;

        while (true) {
            $offset += 1;

            /**
             * @var ImageEloquentModel $image
             */
            $image = $this->newInstance()
                ->newQuery()
                ->offset($offset)
                ->limit(1)
                ->first();

            if ($image) {
                $entity = $entity ? $entity->set($image->toArray()) : $this->getEntity($image->toArray());

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
                $cacheKey = Util::getKey('image', 'mysql', $itm);
                Cache::tags(['image'])->forget($cacheKey);
            }
        } else {
            $cacheKey = Util::getKey('image', 'mysql', $id);
            Cache::tags(['image'])->forget($cacheKey);
        }

        $model = $this->newInstance();

        return $model->destroy($id);
    }
}
