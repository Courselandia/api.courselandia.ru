<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Actions\Admin\User;

use App\Models\Action;
use App\Models\Entity;
use App\Models\Enums\CacheTime;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\User\Entities\User as UserEntity;
use App\Modules\User\Models\User;
use Cache;
use ReflectionException;
use Util;

/**
 * Чтение пользователей.
 */
class UserReadAction extends Action
{
    /**
     * Сортировка данных.
     *
     * @var array|null
     */
    public ?array $sorts = null;

    /**
     * Фильтрация данных.
     *
     * @var array|null
     */
    public ?array $filters = null;

    /**
     * Начать выборку.
     *
     * @var int|null
     */
    public ?int $offset = null;

    /**
     * Лимит выборки выборку.
     *
     * @var int|null
     */
    public ?int $limit = null;

    /**
     * Метод запуска логики.
     *
     * @return array Вернет результаты исполнения.
     * @throws ParameterInvalidException|ReflectionException
     */
    public function run(): array
    {
        $cacheKey = Util::getKey(
            'user',
            'admin',
            'read',
            'count',
            $this->sorts,
            $this->filters,
            $this->offset,
            $this->limit,
            'verification',
            'role',
        );

        return Cache::tags(['user'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $query = User::filter($this->filters ?: [])
                    ->sorted($this->sorts ?: [])
                    ->with([
                        'verification',
                        'role',
                    ]);

                if ($this->offset) {
                    $query->offset($this->offset);
                }

                if ($this->limit) {
                    $query->limit($this->limit);
                }

                $items = $query->get()->toArray();

                return [
                    'data' => Entity::toEntities($items, new UserEntity()),
                    'total' => $query->count(),
                ];
            }
        );
    }
}
