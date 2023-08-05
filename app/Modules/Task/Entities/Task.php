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
     * Школа.
     *
     * @var User|null
     */
    public ?User $user = null;
}
