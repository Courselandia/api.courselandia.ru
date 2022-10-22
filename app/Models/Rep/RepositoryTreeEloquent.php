<?php
/**
 * Ядро базовых классов.
 * Этот пакет содержит ядро базовых классов для работы с основными компонентами и возможностями системы.
 *
 * @package App.Models
 */

namespace App\Models\Rep;

use App\Models\Exceptions\RecordNotExistException;

/**
 * Трейт репозитория работающий с Eloquent для древовидных структур.
 */
trait RepositoryTreeEloquent
{
    use RepositoryEloquent;

    /**
     * Получение всех родителей страницы.
     *
     * @param  int|string  $id  ID страницы у которой нужно получить всех родителей.
     *
     * @return array Вернет массив страниц.
     */
    protected function _parents(int|string $id): array
    {
        /**
         * @var Repository $this
         */
        return $this->getModel()->find($id)->ancestors->toArray();
    }

    /**
     * Получение родителя.
     *
     * @param  int|string  $id  |string ID записи у которой нужно получить всех потомков.
     *
     * @return array Вернет запись.
     */
    protected function _parent(int|string $id): array
    {
        /**
         * @var Repository $this
         */
        return $this->getModel()->find($id)->parent->toArray();
    }

    /**
     * Получение всех детей.
     *
     * @param  int|string  $id  ID записи у которой нужно получить всех потомков.
     *
     * @return array Вернет массив записей.
     */
    protected function _children(int|string $id): array
    {
        /**
         * @var Repository $this
         */
        return $this->getModel()->find($id)->chidren->toArray();
    }

    /**
     * Получение всех потомков.
     *
     * @param  int|string  $id  ID записи у которой нужно получить всех потомков.
     *
     * @return array Вернет массив записей.
     */
    protected function _descendants(int|string $id): array
    {
        /**
         * @var Repository $this
         */
        $item = $this->getModel()->find($id);

        if ($item) {
            return $item->descendants->toArray();
        }

        return [];
    }

    /**
     * Получение всех предков.
     *
     * @param  int|string  $id  ID записи у которой нужно получить всех потомков.
     *
     * @return array Вернет массив записей.
     */
    protected function _ancestors(int|string $id): array
    {
        /**
         * @var Repository $this
         */
        $item = $this->getModel()->find($id);

        if ($item) {
            return $item->ancestors->toArray();
        }

        return [];
    }

    /**
     * Поднятие узла.
     *
     * @param  int|string  $id  ID страницы у которой нужно получить всех потомков.
     * @param  int  $amount  На какое количество поднять узел.
     * @param  null  $scopeName  Название пространства узла в рамках которого нужно произвести изменение.
     * @param  int|string|null  $scopeId  Значение пространства узла в рамках которого нужно произвести изменение.
     *
     * @return bool Вернет успешность операции.
     * @throws RecordNotExistException
     */
    protected function _up(int|string $id, int $amount = 1, $scopeName = null, int|string $scopeId = null): bool
    {
        /**
         * @var Repository $this
         */
        $model = $this->getModel();

        if ($scopeName && $scopeId) {
            $model = $model->where($scopeName, $scopeId);
        }

        $model = $model->find($id);

        if ($model) {
            $model->up($amount);
        } else {
            throw new RecordNotExistException(trans('models.repositoryTreeEloquent.node_not_exist'));
        }

        return true;
    }

    /**
     * Опускание узла.
     *
     * @param  int|string  $id  ID страницы у которой нужно получить всех потомков.
     * @param  int  $amount  На какое количество опустить узел.
     * @param  null  $scopeName  Название пространства узла в рамках которого нужно произвести изменение.
     * @param  int|string|null  $scopeId  Значение пространства узла в рамках которого нужно произвести изменение.
     *
     * @return bool Вернет успешность операции.
     * @throws RecordNotExistException
     */
    protected function _down(int|string $id, int $amount = 1, $scopeName = null, int|string $scopeId = null): bool
    {
        /**
         * @var Repository $this
         */
        $model = $this->getModel();

        if ($scopeName && $scopeId) {
            $model = $model->where($scopeName, $scopeId);
        }

        $model = $model->find($id);

        if ($model) {
            $model->down($amount);
        } else {
            throw new RecordNotExistException(trans('models.repositoryTreeEloquent.node_not_exist'));
        }

        return true;
    }

    /**
     * Ремонтирование дерева.
     *
     * @return bool Вернет успешность операции.
     */
    protected function _fix(): bool
    {
        /**
         * @var Repository $this
         */
        $model = $this->getModel();
        $model->fixTree();

        return true;
    }
}
