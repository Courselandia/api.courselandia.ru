<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Actions\Admin\User;

use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Models\Enums\DateGroup;
use App\Models\Enums\DatePeriod;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\User\Entities\UserAnalytics;
use App\Modules\User\Repositories\User;
use Cache;
use Carbon\Carbon;
use ReflectionException;
use Util;

/**
 * Класс для получения аналитики новых пользователей.
 */
class UserAnalyticsNewUsersAction extends Action
{
    /**
     * Репозиторий пользователей.
     *
     * @var User|null
     */
    private ?User $user;

    /**
     * Группировка.
     *
     * @var DateGroup
     */
    public DateGroup $group = DateGroup::DAY;

    /**
     * Период.
     *
     * @var DatePeriod|null
     */
    public ?DatePeriod $datePeriod = null;

    /**
     * Начальная дата.
     *
     * @var Carbon|null
     */
    public ?Carbon $dateFrom = null;

    /**
     * Конечная дата.
     *
     * @var Carbon|null
     */
    public ?Carbon $dateTo = null;

    /**
     * Конструктор.
     *
     * @param  User  $user  Репозиторий пользователей.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Метод запуска логики.
     *
     * @return UserAnalytics[] Вернет результаты исполнения.
     * @throws ParameterInvalidException|ReflectionException
     */
    public function run(): array
    {
        $cacheKey = Util::getKey(
            'User',
            'AnalyticsNewUsers',
            $this->group,
            $this->datePeriod,
            $this->dateFrom,
            $this->dateTo
        );

        return Cache::tags(['user'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                return $this->user->analyticsNewUsers($this->group, $this->datePeriod, $this->dateFrom, $this->dateTo);
            }
        );
    }
}
