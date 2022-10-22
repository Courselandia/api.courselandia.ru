<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Entities;

use App\Models\Entity;
use Carbon\Carbon;

/**
 * Сущность для аутентификации пользователя.
 */
class UserAuth extends Entity
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
     * Операционная система.
     *
     * @var string|null
     */
    public ?string $os = null;

    /**
     * Устройство.
     *
     * @var string|null
     */
    public ?string $device = null;

    /**
     * Браузер.
     *
     * @var string|null
     */
    public ?string $browser = null;

    /**
     * Агент.
     *
     * @var string|null
     */
    public ?string $agent = null;

    /**
     * IP.
     *
     * @var string|null
     */
    public ?string $ip = null;

    /**
     * Широта.
     *
     * @var float|null
     */
    public ?float $latitude = null;

    /**
     * Долгота.
     *
     * @var float|null
     */
    public ?float $longitude = null;

    /**
     * Код страны.
     *
     * @var string|null
     */
    public ?string $country_code = null;

    /**
     * Код региона.
     *
     * @var string|null
     */
    public ?string $region_code = null;

    /**
     * Город.
     *
     * @var string|null
     */
    public ?string $city = null;

    /**
     * Индекс.
     *
     * @var string|null
     */
    public ?string $zip = null;

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
}