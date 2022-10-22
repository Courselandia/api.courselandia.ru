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
use ReflectionException;
use App\Models\Enums\CacheTime;
use App\Models\Enums\SortDirection;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Rep\RepositoryQueryBuilder;
use App\Modules\Alert\Repositories\Alert;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Alert\Entities\Alert as AlertEntity;

/**
 * Класс для работы с предупреждениями.
 */
class AlertImplement
{
    /**
     * Репозиторий работы с предупреждениями.
     *
     * @var Alert
     */
    protected Alert $alert;

    /**
     * Конструктор.
     *
     * @param  Alert  $alert  Репозиторий работы с предупреждениями.
     */
    public function __construct(Alert $alert)
    {
        $this->alert = $alert;
    }

    /**
     * Добавить предупреждение.
     *
     * @param  string  $title  Заголовок.
     * @param  bool  $status  Если поставить true то будет иметь статус не прочитанного.
     * @param  string|null  $description  Описание.
     * @param  string|null  $url  Ссылка.
     * @param  string|null  $tag  Тэг.
     * @param  string|null  $color  Цвет тэга.
     *
     * @return int Вернет ID последней вставленной строки.
     * @throws ParameterInvalidException
     */
    public function add(
        string $title,
        bool $status = true,
        string $description = null,
        string $url = null,
        string $tag = null,
        string $color = null
    ): int {
        $alertEntity = new AlertEntity();
        $alertEntity->title = $title;
        $alertEntity->status = $status;
        $alertEntity->description = $description;
        $alertEntity->url = $url;
        $alertEntity->tag = $tag;
        $alertEntity->color = $color;

        $id = $this->alert->create($alertEntity);
        Cache::tags(['alert'])->flush();

        return $id;
    }

    /**
     * Удалить предупреждение.
     *
     * @param  int|array  $id  ID предупреждения.
     *
     * @return bool Вернет булево значение успешности операции.
     */
    public function remove(int|array $id): bool
    {
        $result = $this->alert->destroy($id);
        Cache::tags(['alert'])->flush();

        return $result;
    }

    /**
     * Установить статус предупреждения как прочитанный.
     *
     * @param  int|string  $id  ID предупреждения.
     * @param  bool  $status  Если поставить true, то будет иметь статус не прочитанного.
     *
     * @return bool Вернет успешность установки статуса.
     * @throws RecordNotExistException
     * @throws ParameterInvalidException
     * @throws ReflectionException
     */
    public function setStatus(int|string $id, bool $status = true): bool
    {
        $alert = $this->get($id);

        if ($alert) {
            $alert->status = $status;
            $this->alert->update($id, $alert);
            Cache::tags(['alert'])->flush();

            return true;
        }

        throw new RecordNotExistException(trans('alert::models.alertImplement.notExist'));
    }

    /**
     * Получение предупреждения по его ID.
     *
     * @param  int|string  $id  ID предупреждения.
     *
     * @return AlertEntity|null Вернет сущность предупреждения.
     * @throws ParameterInvalidException
     * @throws ReflectionException
     */
    public function get(int|string $id): ?AlertEntity
    {
        $query = new RepositoryQueryBuilder($id);

        $cacheKey = Util::getKey('alert', $query);

        return Cache::tags(['access', 'user'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($query) {
                return $this->alert->get($query);
            }
        );
    }

    /**
     * Получить список предупреждений.
     *
     * @param  int|null  $offset  Отступ вывода.
     * @param  int|null  $limit  Лимит вывода.
     * @param  bool  $status  Если установить true, то получит только прочитанные.
     *
     * @return AlertEntity[] Вернет массив данных предупреждений.
     * @throws ParameterInvalidException
     */
    public function list(int $offset = null, int $limit = null, bool $status = null): array
    {
        $query = new RepositoryQueryBuilder();
        $query->setOffset($offset)
            ->setLimit($limit)
            ->addSort('created_at', SortDirection::DESC);

        if (isset($status)) {
            $query->setActive($status);
        }

        $cacheKey = Util::getKey('alert', $query);

        return Cache::tags(['alert'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($query) {
                return $this->alert->read($query);
            }
        );
    }
}
