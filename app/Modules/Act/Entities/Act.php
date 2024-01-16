<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\Act\Entities;

use App\Models\EntityNew;
use Carbon\Carbon;

/**
 * Сущность для действий.
 */
class Act extends EntityNew
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
     * @var int|null
     */
    public ?int $minutes = null;

    /**
     * Дата обновления.
     *
     * @var Carbon|null
     */
    public ?Carbon $updated_at = null;

    /**
     * @param string|int|null $id ID.
     * @param string|null $index Индекс.
     * @param int|null $count Количество.
     * @param int|null $minutes Минут.
     * @param Carbon|null $updated_at Дата обновления.
     */
    public function __construct(
        string|int|null $id = null,
        ?string $index = null,
        ?int $count = null,
        ?int $minutes = null,
        ?Carbon $updated_at = null
    )
    {
        $this->id = $id;
        $this->index = $index;
        $this->count = $count;
        $this->minutes = $minutes;
        $this->updated_at = $updated_at;
    }
}
