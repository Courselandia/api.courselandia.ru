<?php
/**
 * Модуль География.
 * Этот модуль содержит все классы для работы с странами, районами, городами и т.д.
 *
 * @package App\Modules\Location
 */

namespace App\Modules\Location\Actions\Admin;

use App\Models\Action;
use App\Modules\Location\Models\Location;
use App\Modules\Location\Values\Country;

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
    private string $country;

    /**
     * Конструктор.
     *
     * @param Location $location Модель локализации.
     * @param string $country Страна.
     */
    public function __construct(Location $location, string $country)
    {
        $this->location = $location;
        $this->country = $country;
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
