<?php
/**
 * Ядро базовых классов.
 * Этот пакет содержит ядро базовых классов для работы с основными компонентами и возможностями системы.
 *
 * @package App.Models
 */

namespace App\Models\Rep;

use ReflectionException;
use App\Models\Exceptions\RecordNotExistException;
use App\Models\Exceptions\ParameterInvalidException;

/**
 * Трейт репозитория работающий с Eloquent.
 */
trait RepositoryFlag
{
    /**
     * Получить все флаги.
     *
     * @param  RepositoryQueryBuilder  $repositoryQueryBuilder  Конструктор запроса к репозиторию.
     *
     * @return array Массив данных.
     * @throws ParameterInvalidException|ReflectionException
     */
    public function flags(RepositoryQueryBuilder $repositoryQueryBuilder): array
    {
        $result = $this->get($repositoryQueryBuilder);

        if ($result?->flags) {
            return $result->flags;
        }

        return [];
    }

    /**
     * Установить все флаги.
     *
     * @param  int|string  $id  Первичный ключ.
     * @param  array  $flags  Массив флагов.
     *
     * @return bool Вернет успешность выполнения операции.
     * @throws RecordNotExistException
     */
    public function setFlags(int|string $id, array $flags): bool
    {
        $model = $this->getModel()->find($id);

        if ($model) {
            $model->setFlags($flags);
            $model->save();

            return true;
        }

        throw new RecordNotExistException(trans('modules.repositoryEloquent.record_exist'));
    }

    /**
     * Добавить значение флагу.
     *
     * @param  int|string  $id  Первичный ключ.
     * @param  string  $name  Название флага.
     * @param  mixed  $value  Значение флага.
     *
     * @return bool Вернет успешность выполнения операции.
     * @throws RecordNotExistException
     */
    public function addFlag(int|string $id, string $name, mixed $value): bool
    {
        $model = $this->getModel()->find($id);

        if ($model) {
            $model->addFlag($name, $value);
            $model->save();

            return true;
        }

        throw new RecordNotExistException(trans('modules.repositoryEloquent.record_exist'));
    }

    /**
     * Проверит, существует ли флаг.
     *
     * @param  int|string  $id  Первичный ключ.
     * @param  string  $name  Название флага.
     *
     * @return bool Вернет результат проверки.
     * @throws RecordNotExistException
     */
    public function checkFlag(int|string $id, string $name): bool
    {
        $model = $this->getModel()->find($id);

        if ($model) {
            $model->checkFlag($name);
            $model->save();

            return true;
        }

        throw new RecordNotExistException(trans('modules.repositoryEloquent.record_exist'));
    }

    /**
     * Удалит флаг.
     *
     * @param  int|string  $id  Первичный ключ.
     * @param  string  $name  Название флага.
     *
     * @return bool Вернет успешность выполнения операции.
     * @throws RecordNotExistException
     */
    public function removeFlag(int|string $id, string $name): bool
    {
        $model = $this->getModel()->find($id);

        if ($model) {
            $model->removeFlag($name);
            $model->save();

            return true;
        }

        throw new RecordNotExistException(trans('modules.repositoryEloquent.record_exist'));
    }
}
