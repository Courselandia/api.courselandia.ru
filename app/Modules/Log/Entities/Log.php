<?php
/**
 * Модуль Логирование.
 * Этот модуль содержит все классы для работы с логированием.
 *
 * @package App\Modules\Log
 */

namespace App\Modules\Log\Entities;

use App\Models\Entity;
use Carbon\Carbon;
use App\Modules\User\Enums\Role;

/**
 * Сущность для логов.
 */
class Log extends Entity
{
    /**
     * ID записи.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * Сообщение.
     *
     * @var string|null
     */
    public string|null $message = null;

    /**
     * Канал.
     *
     * @var string|null
     */
    public string|null $channel = null;

    /**
     * Уровень.
     *
     * @var string|null
     */
    public string|null $level = null;

    /**
     * Название уровня лога.
     *
     * @var string|null
     */
    public string|null $level_name = null;

    /**
     * Дата в Unix формате.
     *
     * @var string|null
     */
    public string|null $unix_time = null;

    /**
     * Дата записи.
     *
     * @var string|null
     */
    public string|null $datetime = null;

    /**
     * Контекст.
     *
     * @var array|null
     */
    public array|null $context = null;

    /**
     * Дополнительные данные.
     *
     * @var string|null
     */
    public string|null $extra = null;

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
}