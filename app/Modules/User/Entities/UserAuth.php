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

    /**
     * @param int|string|null $id ID записи.
     * @param int|string|null $user_id ID пользователя.
     * @param string|null $os Операционная система.
     * @param string|null $device Устройство.
     * @param string|null $browser Браузер.
     * @param string|null $agent Агент.
     * @param string|null $ip IP.
     * @param float|null $latitude Широта.
     * @param float|null $longitude Долгота.
     * @param string|null $country_code Код страны.
     * @param string|null $region_code Код региона.
     * @param string|null $city Город.
     * @param string|null $zip Индекс.
     * @param Carbon|null $created_at Дата создания.
     * @param Carbon|null $updated_at Дата обновления.
     * @param Carbon|null $deleted_at Дата удаления.
     */
    public function __construct(
        int|string|null $id = null,
        int|string|null $user_id = null,
        ?string         $os = null,
        ?string         $device = null,
        ?string         $browser = null,
        ?string         $agent = null,
        ?string         $ip = null,
        ?float          $latitude = null,
        ?float          $longitude = null,
        ?string         $country_code = null,
        ?string         $region_code = null,
        ?string         $city = null,
        ?string         $zip = null,
        ?Carbon         $created_at = null,
        ?Carbon         $updated_at = null,
        ?Carbon         $deleted_at = null,
    )
    {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->os = $os;
        $this->device = $device;
        $this->browser = $browser;
        $this->agent = $agent;
        $this->ip = $ip;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->country_code = $country_code;
        $this->region_code = $region_code;
        $this->city = $city;
        $this->zip = $zip;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
        $this->deleted_at = $deleted_at;
    }
}
