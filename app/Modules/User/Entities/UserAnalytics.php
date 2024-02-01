<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Entities;

use App\Models\EntityNew;

/**
 * Сущность для статистики новых пользователей.
 */
class UserAnalytics extends EntityNew
{
    /**
     * Дата.
     *
     * @var string|null
     */
    public ?string $date_group = null;

    /**
     * Количество.
     *
     * @var int|null
     */
    public ?int $amount = null;

    /**
     * @param string|null $date_group Дата.
     * @param int|null $amount Количество.
     */
    public function __construct(
        ?string $date_group = null,
        ?int    $amount = null
    )
    {
        $this->date_group = $date_group;
        $this->amount = $amount;
    }
}
