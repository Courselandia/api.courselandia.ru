<?php
/**
 * Модуль предупреждений.
 * Этот модуль содержит все классы для работы с предупреждениями.
 *
 * @package App\Modules\Alert
 */

namespace App\Modules\Alert\Models;

use Cache;
use Util;
use App\Models\Enums\CacheTime;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Alert\Entities\Alert as AlertEntity;

/**
 * Класс для работы с предупреждениями.
 */
class AlertImplement
{
    /**
     * Добавить предупреждение.
     *
     * @param string $title Заголовок.
     * @param bool $status Если поставить true то будет иметь статус не прочитанного.
     * @param string|null $description Описание.
     * @param string|null $url Ссылка.
     * @param string|null $tag Тэг.
     * @param string|null $color Цвет тэга.
     *
     * @return int Вернет ID последней вставленной строки.
     */
    public function add(
        string $title,
        bool   $status = true,
        string $description = null,
        string $url = null,
        string $tag = null,
        string $color = null
    ): int
    {
        $alertEntity = new AlertEntity();
        $alertEntity->title = $title;
        $alertEntity->status = $status;
        $alertEntity->description = $description;
        $alertEntity->url = $url;
        $alertEntity->tag = $tag;
        $alertEntity->color = $color;

        $alert = Alert::create($alertEntity->toArray());
        Cache::tags(['alert'])->flush();

        return $alert->id;
    }

    /**
     * Удалить предупреждение.
     *
     * @param int|array $id ID предупреждения.
     *
     * @return bool Вернет булево значение успешности операции.
     */
    public function remove(int|array $id): bool
    {
        $result = Alert::destroy($id);
        Cache::tags(['alert'])->flush();

        return $result;
    }

    /**
     * Установить статус предупреждения как прочитанный.
     *
     * @param int|string $id ID предупреждения.
     * @param bool $status Если поставить true, то будет иметь статус не прочитанного.
     *
     * @return bool Вернет успешность установки статуса.
     * @throws RecordNotExistException
     */
    public function setStatus(int|string $id, bool $status = true): bool
    {
        $alertEntity = $this->get($id);

        if ($alertEntity) {
            $alertEntity->status = $status;
            $alert = Alert::find($id);
            $alert->update($alert->toArray());
            Cache::tags(['alert'])->flush();

            return true;
        }

        throw new RecordNotExistException(trans('alert::models.alertImplement.notExist'));
    }

    /**
     * Получение предупреждения по его ID.
     *
     * @param int|string $id ID предупреждения.
     *
     * @return AlertEntity|null Вернет сущность предупреждения.
     */
    public function get(int|string $id): ?AlertEntity
    {
        $cacheKey = Util::getKey('alert', 'get', $id);

        return Cache::tags(['access', 'user'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($id) {
                $item = Alert::find($id);

                return $item ? AlertEntity::from($item->toArray()) : null;
            }
        );
    }

    /**
     * Получить список предупреждений.
     *
     * @param int|null $offset Отступ вывода.
     * @param int|null $limit Лимит вывода.
     * @param bool $status Если установить true, то получит только прочитанные.
     *
     * @return array<int, AlertEntity> Вернет массив данных предупреждений.
     */
    public function list(int $offset = null, int $limit = null, bool $status = null): array
    {
        $cacheKey = Util::getKey('alert', 'list', $offset, $limit, $status);

        return Cache::tags(['alert'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($offset, $limit, $status) {
                $query = Alert::orderBy('created_at', 'DESC');

                if ($offset) {
                    $query->offset($offset);
                }

                if ($limit) {
                    $query->limit($limit);
                }

                if (isset($status)) {
                    $query->active();
                }

                return AlertEntity::collect($query->get()->toArray());
            }
        );
    }
}
