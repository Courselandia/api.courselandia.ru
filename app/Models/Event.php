<?php
/**
 * Ядро базовых классов.
 * Этот пакет содержит ядро базовых классов для работы с основными компонентами и возможностями системы.
 *
 * @package App.Models
 */

namespace App\Models;

/**
 * Трейт позволяющий добавлять и запускать события внутри модели.
 */
trait Event
{
    /**
     * Объект интерфейса определяющий методы для добавления, удаления и оповещения наблюдателей.
     *
     * @var Observable
     */
    private Observable $observable;

    private function init(): void
    {
        if (!isset($this->observable)) {
            $this->observable = new Observable();
        }
    }

    /**
     * Добавление событий.
     *
     * @param string $action Название события. Если $function пуст, то реализация события происходит через одноименный метод.
     * @param callable|null $function Функция, которая должна быть вызвана для этого события.
     *
     * @return $this
     */
    public function addEvent(string $action, callable $function = null): static
    {
        $this->init();
        $this->observable->add($this, $action, $function);

        return $this;
    }

    /**
     * Удаление события.
     *
     * @param string $action Название события.
     *
     * @return $this
     */
    public function deleteEvent(string $action): static
    {
        $this->init();
        $this->observable->delete($action);

        return $this;
    }

    /**
     * Проверить если такое событие.
     *
     * @param string $action Название события.
     *
     * @return bool Вернет true если событие для наблюдателя существует.
     */
    public function hasEvent(string $action): bool
    {
        $this->init();

        return $this->observable->has($action);
    }

    /**
     * Запуск события и возращения всех значений.
     *
     * @param string $action Название события.
     * @param array $params Параметры события, которые передаются в его реализацию.
     *
     * @return array|bool Вернет все возращенные значения реализаций.
     */
    public function fireEvent(string $action, array $params = []): array|bool
    {
        $this->init();

        return $this->observable->fire($action, $params);
    }

    /**
     * Запуск события и возращения только первого значения.
     *
     * @param string $action Название события.
     * @param array $params Параметры события, которые передаются в его реализацию.
     *
     * @return mixed Вернет первое возращенное значения реализаций.
     */
    public function firstEvent(string $action, array $params = []): mixed
    {
        $this->init();

        return $this->observable->first($action, $params);
    }

    /**
     * Запуск события и их исполнение до первого возращенного false.
     *
     * @param string $action Название события.
     * @param array $params Параметры события, которые передаются в его реализацию.
     *
     * @return mixed Вернет первое возращенное значения реализаций.
     */
    public function untilEvent(string $action, array $params = []): mixed
    {
        $this->init();

        return $this->observable->until($action, $params);
    }
}
