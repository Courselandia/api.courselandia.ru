<?php
/**
 * Кеширование.
 * Этот пакет содержит драйвера для различных способов хранения кеша.
 *
 * @package App.Models.Caches
 */

namespace App\Models\Caches;

use DB;
use Config;
use DateTime;
use DateInterval;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Cache\TaggableStore;
use MongoDB\Laravel\Connection;
use MongoDB\BSON\UTCDateTime;
use MongoDB\Laravel\Query\Builder;

/**
 * Класс драйвер кеша на основе Memcache.
 */
class CacheMongoDb extends TaggableStore implements Store
{
    /**
     * Объект кеширования на основе Memcache.
     *
     * @var Connection
     */
    private Connection $connection;

    /**
     * Название индекса, который хранит кеш.
     *
     * @var string
     */
    private const string INDEX_CACHE = 'cache';

    /**
     * Конструктор.
     */
    public function __construct()
    {
        $this->setConnection();
    }

    /**
     * Создание соединения с сервером кеширования.
     *
     * @return bool Возвращает статус удачности соединения.
     */
    public function setConnection(): bool
    {
        $connection = DB::connection(Config::get('database.connections.mongodb.driver'));

        if ($connection) {
            /**
             * @var Connection $connection
             */
            $this->_setConnection($connection);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Получение кеша по ключу.
     *
     * @param  string  $key  Ключ.
     *
     * @return mixed Значение ключа.
     */
    public function get($key): mixed
    {
        $index = $this->getPrefix().$key;
        $result = $this->getCollection()->where('key', $index)->first();

        if (!is_null($result)) {
            if (microtime(true) >= $result['expiration']->toDateTime()->format('U')) {
                $this->forget($key);
                return null;
            } elseif ($result['value']) {
                return unserialize($result['value']);
            } else {
                return null;
            }
        }

        return null;
    }

    /**
     * Запись кеша.
     *
     * @param  string  $key  Ключ.
     * @param  mixed  $value  Значение кеша.
     * @param  int  $seconds  Количество секунд, на которые нужно запомнить кеш.
     *
     * @return bool Статус удачности записи.
     * @throws
     */
    public function put($key, $value, $seconds): bool
    {
        $index = $this->getPrefix().$key;
        $dateTime = new DateTime();
        $dateInterval = new DateInterval('PT'.$seconds.'S');
        $dateTime->add($dateInterval);
        $expiration = new UTCDateTime($dateTime);

        $data = [
            'expiration' => $expiration,
            'key' => $index,
            'value' => serialize($value)
        ];

        $item = $this->getCollection()->where('key', $index)->first();

        if (is_null($item)) {
            $status = $this->getCollection()->insert($data);
        } else {
            $status = $this->getCollection()->where('key', $index)->update($data);
        }

        return $status;
    }

    /**
     * Прибавление к значению.
     *
     * @param  string  $key  Ключ.
     * @param  int  $value  Значение инкремента.
     *
     * @return int Значение после инкриминирования.
     */
    public function increment($key, $value = 1): int
    {
        $value = $this->get($key);

        if (is_numeric($value)) {
            $value++;
        }

        return $this->put($key, $value, 5256000);
    }

    /**
     * Отнимание от значения.
     *
     * @param  string  $key  Ключ.
     * @param  int  $value  Значение инкремента.
     *
     * @return int Значение после прибавления.
     */
    public function decrement($key, $value = 1): int
    {
        $value = $this->get($key);

        if (is_numeric($value)) {
            $value--;
        }

        return $this->put($key, $value, 5256000);
    }

    /**
     * Запись кеша на неограниченное количество времени.
     *
     * @param  string  $key  Ключ.
     * @param  mixed  $value  Значение кеша.
     *
     * @return bool Статус удачности записи.
     */
    public function forever($key, $value): bool
    {
        return $this->put($key, $value, 5256000);
    }

    /**
     * Удаление кеша по ключу.
     *
     * @param  string  $key  Ключ.
     *
     * @return bool Статус удачности удаления.
     */
    public function forget($key): bool
    {
        $index = $this->getPrefix().$key;
        $item = $this->getCollection()->where('key', $index)->first();

        if (!is_null($item)) {
            return $this->getCollection()->where('key', $index)->delete();
        }

        return true;
    }

    /**
     * Полная очистка закешированных данных.
     *
     * @return bool Статус удачности очистки.
     */
    public function flush(): bool
    {
        $collection = $this->getConnection()->getCollection(Config::get('cache.stores.mongodb.table'));
        $status = $collection->drop();

        return (bool)$status;
    }

    /**
     * Получение префикса кеширования для проекта.
     *
     * @return string Префикс для кеширования.
     */
    public function getPrefix(): string
    {
        return self::INDEX_CACHE;
    }

    /**
     * Получение закешированных данных по набору из ключей.
     *
     * @param  array  $keys  Ключи для получения данных.
     *
     * @return array Массив значений для введенных ключей.
     */
    public function many(array $keys): array
    {
        $data = [];

        for ($i = 0; $i < count($keys); $i++) {
            $data[$keys[$i]] = $this->get($keys[$i]);
        }

        return $data;
    }

    /**
     * Сохранение закешированных данных по набору из значений.
     *
     * @param  array  $values  Массив данных с ключами.
     * @param  int  $seconds  Количество секунд, на которые нужно запомнить кеш.
     *
     * @return bool Вернет статус удачности операции.
     *
     * @return void
     */
    public function putMany(array $values, $seconds): bool
    {
        foreach ($values as $key => $value) {
            $this->put($key, $value, $seconds);
        }

        return true;
    }

    /**
     * Получение объекта кеширования на основе Memcache.
     *
     * @return Connection Объект соединения с базой данных MongoDb.
     */
    public function getConnection(): Connection
    {
        return $this->connection;
    }

    /**
     * Получение объекта кеширования на основе Memcache.
     *
     * @param  Connection  $connection  Объект соединения с базой данных MongoDb.
     *
     * @return void
     */
    private function _setConnection(Connection $connection): void
    {
        $this->connection = $connection;
    }

    /**
     * Получение коллекции базы данных.
     *
     * @return Builder
     */
    protected function getCollection(): Builder
    {
        return $this->getConnection()->collection(Config::get('cache.stores.mongodb.table'));
    }
}
