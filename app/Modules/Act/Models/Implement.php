<?php
/**
 * Модуль Запоминания действий.
 * Этот модуль содержит все классы для работы с запоминанием и контролем действий пользователя.
 *
 * @package App\Modules\Act
 */

namespace App\Modules\Act\Models;

use Util;
use Cache;
use Request;
use Carbon\Carbon;
use ReflectionException;
use App\Models\Enums\CacheTime;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordNotExistException;
use App\Models\Rep\RepositoryCondition;
use App\Models\Rep\RepositoryQueryBuilder;
use App\Modules\Act\Entities\Act as ActEntity;
use App\Modules\Act\Repositories\Act;

/**
 * Класс запоминания действий пользователя.
 * Класс, который позволяет вести учет действий пользователя, если требуется контролировать, сколько раз он имеет право
 * его выполнить.
 */
class Implement
{
    /**
     * Репозиторий действий.
     *
     * @var Act
     */
    private Act $act;

    /**
     * Конструктор.
     *
     * @param  Act  $act  Репозиторий действий.
     */
    public function __construct(Act $act)
    {
        $this->act = $act;
    }

    /**
     * Проверка статуса действий.
     * Позволяет определить, сколько раз выполнялось действие и может ли действие снова быть осуществлено.
     *
     * @param  string  $index  Индекс действия.
     * @param  int  $maxCount  Сколько раз это действий может быть исполнено.
     * @param  int  $minutes  Через сколько минут это действие будет доступно.
     *
     * @return bool Если вернет true, то действие может быть выполнено еще раз. Если false, то максимальный порог его выполнения достигнут.
     * @throws ParameterInvalidException
     * @throws ReflectionException
     */
    public function status(string $index, int $maxCount, int $minutes = 60): bool
    {
        $index = $this->getKey($index);
        $actEntity = $this->read($index);

        if ($actEntity) {
            $timeCurrent = Carbon::now();
            $timeEnd = $actEntity->updated_at->addMinutes($minutes);

            if ($timeCurrent >= $timeEnd) {
                $this->clean($index);
                return true;
            }

            if ($actEntity->count < $maxCount) {
                return true;
            }

            return false;
        }

        return true;
    }

    /**
     * Добавление действия.
     *
     * @param  string  $index  Индекс действия.
     * @param  int  $to  Добавить к количеству выполненных действий.
     * @param  int  $minutes  Общее время жизни этой записи в минутах.
     *
     * @return Implement
     * @throws RecordNotExistException
     * @throws ParameterInvalidException
     * @throws ReflectionException
     */
    public function add(string $index, int $to = 1, int $minutes = 60 * 24 * 31): Implement
    {
        $index = $this->getKey($index);

        $actEntity = new ActEntity();
        $actEntity->count = 0;
        $actEntity->minutes = $minutes;

        $actEntity = $this->read($index, $actEntity);

        $actEntity->count += $to;
        $this->set($index, $actEntity->count, $actEntity->minutes);

        return $this;
    }

    /**
     * Очистка истории действий.
     * Позволяет удалить всю историю об этом действии, заодно обнулив весь результат.
     *
     * @param  string  $index  Индекс действия.
     *
     * @return Implement
     * @throws ParameterInvalidException|ReflectionException
     */
    public function delete(string $index): Implement
    {
        $index = $this->getKey($index);
        $this->clean($index);

        return $this;
    }

    /**
     * Получение текущего количества выполненных действий.
     *
     * @param  string  $index  Индекс действия.
     *
     * @return int Вернет текущее количество.
     * @throws ParameterInvalidException
     * @throws ReflectionException
     */
    public function get(string $index): int
    {
        $index = $this->getKey($index);
        $actEntity = $this->read($index);

        return $actEntity ? $actEntity->count : 0;
    }

    /**
     * Получение действия по индексу.
     *
     * @param  string  $index  Индекс действия.
     * @param  ActEntity|null  $default  Значение по умолчанию, если значение отсутствует.
     *
     * @return ActEntity|null Возвращает сущность действия.
     * @throws ParameterInvalidException
     * @throws ReflectionException
     */
    protected function read(string $index, ?ActEntity $default = null): ?ActEntity
    {
        $query = new RepositoryQueryBuilder();
        $query->addCondition(new RepositoryCondition('index', $index));

        $cacheKey = Util::getKey('act', $query);

        $act = Cache::tags(['act'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($query) {
                return $this->act->get($query);
            }
        );

        if ($act) {
            return $act;
        }

        if ($default) {
            return $default;
        }

        return null;
    }

    /**
     * Запись действия.
     *
     * @param  string  $index  Индекс действия.
     * @param  int  $count  Попыток действий.
     * @param  int  $minutes  Количество минут через которые действие можно будет повторить.
     *
     * @return Implement
     * @throws RecordNotExistException
     * @throws ParameterInvalidException
     * @throws ReflectionException
     */
    protected function set(string $index, int $count, int $minutes): Implement
    {
        $query = new RepositoryQueryBuilder();
        $query->addCondition(new RepositoryCondition('index', $index));

        $cacheKey = Util::getKey('act', $query);

        $act = Cache::tags(['act'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($query) {
                return $this->act->get($query);
            }
        );

        if ($act) {
            $act->index = $index;
            $act->count = $count;
            $act->minutes = $minutes;

            $this->act->update($act->id, $act);
        } else {
            $act = new ActEntity();
            $act->index = $index;
            $act->count = $count;
            $act->minutes = $minutes;

            $this->act->create($act);
        }

        Cache::tags(['act'])->flush();

        return $this;
    }

    /**
     * Очистка истории действий.
     * Позволяет удалить всю историю об этом действии, заодно обнулив весь результат.
     *
     * @param  string  $index  Индекс действия.
     *
     * @return Implement
     * @throws ParameterInvalidException
     * @throws ReflectionException
     */
    protected function clean(string $index): Implement
    {
        $actEntity = $this->read($index);

        if ($actEntity) {
            $this->act->destroy($actEntity->id);
            Cache::tags(['act'])->flush();
        }

        return $this;
    }

    /**
     * Получение ключа по индексу.
     *
     * @param  string  $index  Индекс действия.
     *
     * @return string Ключ.
     */
    protected function getKey(string $index): string
    {
        return md5('action.'.$this->getIp().'.'.$index);
    }

    /**
     * Получение IP адреса пользователя.
     *
     * @return string|null Вернет IP пользователя.
     */
    private function getIp(): ?string
    {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            return $_SERVER['REMOTE_ADDR'];
        }

        return Request::ip();
    }
}
