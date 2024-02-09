<?php
/**
 * Модуль Менеджер Заданий.
 * Этот модуль содержит все классы для работы с заданиями.
 *
 * @package App\Modules\Task
 */

namespace App\Modules\Task\Entities;

use App\Models\Entity;
use Carbon\Carbon;
use App\Modules\Task\Enums\Status;
use App\Modules\User\Entities\User;

/**
 * Сущность для задания.
 */
class Task extends Entity
{
    /**
     * ID записи.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * ID пользователя.
     *
     * @var int|string|null
     */
    public int|string|null $user_id = null;

    /**
     * Название.
     *
     * @var string|null
     */
    public ?string $name = null;

    /**
     * Причина провала.
     *
     * @var string|null
     */
    public ?string $reason = null;

    /**
     * Статус.
     *
     * @var Status|null
     */
    public ?Status $status = null;

    /**
     * Дата запуска.
     *
     * @var ?Carbon
     */
    public ?Carbon $launched_at = null;

    /**
     * Дата завершения.
     *
     * @var ?Carbon
     */
    public ?Carbon $finished_at = null;

    /**
     * Дата создания.
     *
     * @var ?Carbon
     */
    public ?Carbon $created_at = null;

    /**
     * Дата обновления.
     *
     * @var ?Carbon
     */
    public ?Carbon $updated_at = null;

    /**
     * Дата удаления.
     *
     * @var ?Carbon
     */
    public ?Carbon $deleted_at = null;

    /**
     * Пользователь.
     *
     * @var User|null
     */
    public ?User $user = null;

    /**
     * @param int|string|null $id ID записи.
     * @param int|string|null $user_id ID пользователя.
     * @param string|null $name Название.
     * @param string|null $reason Причина провала.
     * @param Status|null $status Статус.
     * @param Carbon|null $launched_at Дата запуска.
     * @param Carbon|null $finished_at Дата завершения.
     * @param Carbon|null $created_at Дата создания.
     * @param Carbon|null $updated_at Дата обновления.
     * @param Carbon|null $deleted_at Дата удаления.
     * @param User|null $user Пользователь.
     */
    public function __construct(
        int|string|null $id = null,
        int|string|null $user_id = null,
        ?string         $name = null,
        ?string         $reason = null,
        ?Status         $status = null,
        ?Carbon         $launched_at = null,
        ?Carbon         $finished_at = null,
        ?Carbon         $created_at = null,
        ?Carbon         $updated_at = null,
        ?Carbon         $deleted_at = null,
        ?User           $user = null
    )
    {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->name = $name;
        $this->reason = $reason;
        $this->status = $status;
        $this->launched_at = $launched_at;
        $this->finished_at = $finished_at;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
        $this->deleted_at = $deleted_at;
        $this->user = $user;
    }
}
