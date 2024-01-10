<?php
/**
 * Модуль Логирование.
 * Этот модуль содержит все классы для работы с логированием.
 *
 * @package App\Modules\Log
 */

namespace App\Modules\Log\Repositories;

use App\Models\Entity;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Repository;
use App\Modules\Log\Entities\Log as LogEntity;

/**
 * Класс репозитория для логирования.
 */
class Log extends Repository
{
    /**
     * Чтение логов.
     *
     * @param array|null $filters Фильтры.
     * @param array|null $sorts Сортировки.
     * @param int|null $offset Начать выборку.
     * @param int|null $limit Лимит выборки.
     * @return LogEntity[] Вернет массив логов.
     *
     * @throws ParameterInvalidException
     */
    public function read(?array $filters = null, ?array $sorts = null, ?int $offset = null, ?int $limit = null): array
    {
        $query = $this->newInstance();
        $query->filter($filters);
        $query->sorted($sorts ?: []);

        if ($offset) {
            $query->offset($offset);
        }

        if ($limit) {
            $query->limit($limit);
        }

        $items = $query->get()->toArray();

        return Entity::toEntities($items, new LogEntity());
    }

    /**
     * Вернет количество записей.
     *
     * @param array|null $filters Фильтры.
     *
     * @return int Количество записей.
     * @throws ParameterInvalidException
     */
    public function count(?array $filters = null): int
    {
        $query = $this->newInstance();
        $query->filter($filters);

        return $query->count();
    }

    /**
     * Получение лога.
     *
     * @param string|int $id ID записи.
     *
     * @return ?LogEntity Сущность лога.
     * @throws ParameterInvalidException
     */
    public function get(string|int $id): ?LogEntity
    {
        $log = $this->newInstance()->find($id);

        return $log ? new LogEntity($log) : null;
    }

    /**
     * Удаление лога.
     *
     * @param string|int $id ID записи.
     *
     * @return bool Вернет признак успешного удаления лога.
     * @throws ParameterInvalidException
     */
    public function destroy(string|int $id): bool
    {
        $model = $this->newInstance();

        return $model->destroy($id);
    }
}
