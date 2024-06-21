<?php
/**
 * Ядро базовых классов.
 * Этот пакет содержит ядро базовых классов для работы с основными компонентами и возможностями системы.
 *
 * @package App.Models
 */

namespace App\Models;

/**
 * Класс интерфейс, определяющий методы для добавления, удаления и оповещения наблюдателей.
 */
class Observable
{
    /**
     * Массив всех наблюдателей.
     *
     * @var array
     */
    protected array $observers = [];

    /**
     * Добавление наблюдателя.
     *
     * @param object $observer Объект наблюдатель, в котором будет реализовано событие.
     * @param string $action Название действия. Если $function пуст, то реализация события происходит через одноименный метод.
     * @param callable|null $function Функция, которая должна быть вызвана для этого события.
     *
     * @return Observable Возвращает интерфейс наблюдателя.
     */
    public function add(object $observer, string $action, callable $function = null): Observable
    {
        if (!isset($this->observers[$action])) {
            $this->observers[$action] = [];
        }

        $ln = count($this->observers[$action]);

        $this->observers[$action][$ln] = [
            'obj' => $observer,
            'function' => $function
        ];

        return $this;
    }

    /**
     * Удаление действия наблюдателя.
     *
     * @param string $action Название действия.
     *
     * @return Observable Возвращает интерфейс наблюдателя.
     */
    public function delete(string $action): Observable
    {
        if (isset($this->observers[$action])) {
            unset($this->observers[$action]);
        }

        return $this;
    }

    /**
     * Проверить если такое действие у наблюдателя.
     *
     * @param string $action Название действия.
     *
     * @return bool Вернет true если действие для наблюдателя существует.
     */
    public function has(string $action): bool
    {
        if (isset($this->observers[$action])) {
            return true;
        }

        return false;
    }

    /**
     * Запуск действия.
     *
     * @param string $action Название действия.
     * @param array $params Параметры действия, которые передаются в его реализацию.
     * @param bool $stopIfFalse Если указать true, то нужно остановить выполнение действия если хотя бы одна реализация вернула false.
     *
     * @return array|bool Вернет все возращенные значения реализаций.
     */
    protected function _start(string $action, array $params = [], bool $stopIfFalse = false): array|bool
    {
        if (isset($this->observers[$action])) {
            $values = [];

            for ($i = 0; $i < count($this->observers[$action]); $i++) {
                if ($this->observers[$action][$i]['function']) {
                    $has = true;
                } else {
                    $has = method_exists($this->observers[$action][$i]['obj'], $action);
                }

                if ($has === true) {
                    array_unshift($params, $this->observers[$action][$i]['obj']);

                    if ($this->observers[$action][$i]['function']) {
                        $values[] = call_user_func_array($this->observers[$action][$i]['function'], $params);
                    } else {
                        $values[] = call_user_func_array(array(
                            $this->observers[$action][$i]['obj'],
                            $action
                        ), $params);
                    }

                    if ($stopIfFalse === true && $values[count($values) - 1] === false) {
                        break;
                    }
                }
            }

            if (count($values) > 0) {
                return $values;
            }
        }

        return true;
    }

    /**
     * Запуск действия и возращения всех значений.
     *
     * @param string $action Название действия.
     * @param array $params Параметры действия, которые передаются в его реализацию.
     *
     * @return array|bool Вернет все возращенные значения реализаций.
     */
    public function fire(string $action, array $params = []): bool|array
    {
        return $this->_start($action, $params);
    }

    /**
     * Запуск действия и возращения только первого значения.
     *
     * @param string $action Название действия.
     * @param array $params Параметры действия, которые передаются в его реализацию.
     *
     * @return mixed Вернет первое возращенное значения реализаций.
     */
    public function first(string $action, array $params = []): mixed
    {
        $values = $this->_start($action, $params);

        if (is_array($values)) {
            return $values[0];
        }

        return true;
    }

    /**
     * Запуск действия и их исполнение до первого возращенного false.
     *
     * @param string $action Название действия.
     * @param array $params Параметры действия, которые передаются в его реализацию.
     *
     * @return mixed Вернет первое возращенное значения реализаций.
     */
    public function until(string $action, array $params = []): mixed
    {
        $values = $this->_start($action, $params, true);

        if (is_array($values)) {
            return $values[0];
        }

        return true;
    }
}
