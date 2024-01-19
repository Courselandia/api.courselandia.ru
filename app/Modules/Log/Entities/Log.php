<?php
/**
 * Модуль Логирование.
 * Этот модуль содержит все классы для работы с логированием.
 *
 * @package App\Modules\Log
 */

namespace App\Modules\Log\Entities;

use App\Models\EntityNew;
use Carbon\Carbon;

/**
 * Сущность для логов.
 */
class Log extends EntityNew
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

    /**
     * @param int|string|null $id ID записи.
     * @param string|null $message Сообщение.
     * @param string|null $channel Канал.
     * @param string|null $level Уровень.
     * @param string|null $level_name Название уровня лога.
     * @param string|null $unix_time Дата в Unix формате.
     * @param string|null $datetime Дата записи.
     * @param array|null $context Контекст.
     * @param string|null $extra Дополнительные данные.
     * @param Carbon|null $created_at Дата создания.
     * @param Carbon|null $updated_at Дата обновления.
     */
    public function __construct(
        int|string|null $id = null,
        string|null     $message = null,
        string|null     $channel = null,
        string|null     $level = null,
        string|null     $level_name = null,
        string|null     $unix_time = null,
        string|null     $datetime = null,
        array|null      $context = null,
        string|null     $extra = null,
        ?Carbon         $created_at = null,
        ?Carbon         $updated_at = null
    )
    {
        $this->id = $id;
        $this->message = $message;
        $this->channel = $channel;
        $this->level = $level;
        $this->level_name = $level_name;
        $this->unix_time = $unix_time;
        $this->datetime = $datetime;
        $this->context = $context;
        $this->extra = $extra;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }
}
