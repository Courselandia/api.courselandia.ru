<?php
/**
 * Модуль География.
 * Этот модуль содержит все классы для работы с странами, районами, городами и т.д.
 *
 * @package App\Modules\Location
 */

namespace App\Modules\Location\Actions\Admin;

use App\Models\Action;
use App\Modules\Location\Entities\Country;
use App\Modules\Location\Models\Location;

/**
 * Класс действия для чтения регионов.
 */
class LocationRegionsReadAction extends Action
{
    /**
     * Модель локализации.
     *
     * @var Location
     */
    private Location $location;

    /**
     * Страна.
     *
     * @var string
     */
    public string $country;

    /**
     * Конструктор.
     *
     * @param  Location  $location  Модель локализации.
     */
    public function __construct(Location $location)
    {
        $this->location = $location;
    }

    /**
     * Метод запуска логики.
     *
     * @return Country[] Вернет результаты исполнения.
     */
    public function run(): array
    {
        return $this->location->getRegions($this->country);
    }
}
