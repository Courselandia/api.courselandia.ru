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

/**
 * Класс действия для чтения стран.
 */
class LocationCountriesReadAction extends Action
{
    /**
     * Модель локализации.
     *
     * @var Location
     */
    private Location $location;

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
     * @return array Вернет результаты исполнения.
     */
    public function run(): array
    {
        return $this->location->getCountries();
    }
}
