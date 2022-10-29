<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Repositories;

use App\Models\Entity;
use App\Models\Enums\DateGroup;
use App\Models\Enums\DatePeriod;
use App\Models\Enums\OperatorQuery;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Rep\Repository;
use App\Models\Rep\RepositoryCondition;
use App\Models\Rep\RepositoryFlag;
use App\Models\Rep\RepositoryQueryBuilder;
use Carbon\Carbon;
use App\Models\Rep\RepositoryEloquent;
use Illuminate\Database\Eloquent\Builder;
use App\Modules\User\Entities\User as UserEntity;
use App\Modules\User\Entities\UserAnalytics;
use ReflectionException;

/**
 * Класс репозитория пользователей на основе Eloquent.
 *
 * @method UserEntity|Entity|null get(RepositoryQueryBuilder $repositoryQueryBuilder = null, Entity $entity = null)
 * @method UserEntity[]|Entity[] read(RepositoryQueryBuilder $repositoryQueryBuilder = null, Entity $entity = null)
 */
class User extends Repository
{
    use RepositoryEloquent;
    use RepositoryFlag;

    /**
     * Получение название уникального токена для пользователя.
     *
     * @return string Вернет название токена.
     * @throws ParameterInvalidException
     */
    public function getRememberTokenName(): string
    {
        return $this->newInstance()->getRememberTokenName();
    }

    /**
     * Получение название уникального идентификатора для пользователя.
     *
     * @return string Вернет название идентификатора.
     * @throws ParameterInvalidException
     */
    public function getAuthIdentifierName(): string
    {
        return $this->newInstance()->getAuthIdentifierName();
    }

    /**
     * Получить статистику новых пользователей.
     *
     * @param  DateGroup  $group  Группировка.
     * @param  DatePeriod|null  $datePeriod  Период.
     * @param  Carbon|null  $dateFrom  Начальная дата.
     * @param  Carbon|null  $dateTo  Конечная дата.
     *
     * @return UserAnalytics[] Данные статистики.
     * @throws ParameterInvalidException|ReflectionException
     */
    public function analyticsNewUsers(
        DateGroup $group = DateGroup::DAY,
        DatePeriod $datePeriod = null,
        Carbon $dateFrom = null,
        Carbon $dateTo = null
    ): array {
        $selects = [
            'COUNT(id) AS amount',
        ];

        if ($group === DateGroup::DAY) {
            $selects[] = "DATE_FORMAT(created_at, '%d-%m-%Y') AS date_group";
        } elseif ($group === DateGroup::WEEK) {
            $selects[] = "DATE_FORMAT(created_at, '%v-%m-%Y') AS date_group";
        } elseif ($group === DateGroup::MONTH) {
            $selects[] = "DATE_FORMAT(created_at, '%m-%Y') AS date_group";
        } elseif ($group === DateGroup::YEAR) {
            $selects[] = "DATE_FORMAT(created_at, '%Y') AS date_group";
        }

        if ($datePeriod === DatePeriod::TODAY) {
            $dateFrom = Carbon::now();
            $dateTo = Carbon::now()->endOfDay();
        } elseif ($datePeriod === DatePeriod::YESTERDAY) {
            $dateFrom = Carbon::yesterday();
            $dateTo = Carbon::yesterday();
        } elseif ($datePeriod === DatePeriod::WEEK) {
            $dateFrom = Carbon::now()->subWeek();
            $dateTo = Carbon::now();
        } elseif ($datePeriod === DatePeriod::MONTH) {
            $dateFrom = Carbon::now()->subMonth();
            $dateTo = Carbon::now();
        } elseif ($datePeriod === DatePeriod::QUARTER) {
            $dateFrom = Carbon::now()->subQuarter();
            $dateTo = Carbon::now();
        } elseif ($datePeriod === DatePeriod::YEAR) {
            $dateFrom = Carbon::now()->subYear();
            $dateTo = Carbon::now();
        }

        $repositoryQueryBuilder = new RepositoryQueryBuilder();

        if (isset($dateFrom)) {
            $repositoryQueryBuilder->addCondition(
                new RepositoryCondition('created_at', $dateFrom->hour(0)->minute(0)->second(0), OperatorQuery::GTE)
            );
            $repositoryQueryBuilder->addCondition(
                new RepositoryCondition('created_at', $dateFrom->hour(0)->minute(0)->second(0), OperatorQuery::LTE)
            );
        }

        if (isset($dateTo)) {
            $repositoryQueryBuilder->addCondition(
                new RepositoryCondition('created_at', $dateTo->hour(0)->minute(0)->second(0), OperatorQuery::GTE)
            );
        }

        $repositoryQueryBuilder->addGroup('date_group');

        foreach ($selects as $select) {
            $repositoryQueryBuilder->addSelect($select);
        }

        return $this->read($repositoryQueryBuilder, new UserAnalytics());
    }
}
