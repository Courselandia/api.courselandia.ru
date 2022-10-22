<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\Act\Entities;

use App\Models\Entity;
use Carbon\Carbon;

/**
 * Сущность для действий.
 */
class Act extends Entity
{
    /**
     * Дата.
     *
     * @var string|int|null
     */
    public string|int|null $id = null;

    /**
     * Индекс.
     *
     * @var string|null
     */
    public ?string $index = null;

    /**
     * Количество.
     *
     * @var int|null
     */
    public ?int $count = null;

    /**
     * Минут.
     *
     * @var int
     */
    public ?int $minutes = null;

    /**
     * Дата обновления.
     *
     * @var Carbon|null
     */
    public ?Carbon $updated_at = null;
}