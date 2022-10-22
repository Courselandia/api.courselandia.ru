<?php
/**
 * Модуль География.
 * Этот модуль содержит все классы для работы со странами, районами, городами и т.д.
 *
 * @package App\Modules\Location
 */

namespace App\Modules\Location\Tests\Feature\Http\Controllers;

use Tests\TestCase;

/**
 * Тестирование: Класс контроллер для географических объектов.
 */
class LocationControllerTest extends TestCase
{
    /**
     * Получение списка стран.
     *
     * @return void
     */
    public function testCountries(): void
    {
        $this->json(
            'GET',
            'api/private/location/countries',
            [],
            [
            ]
        )->assertStatus(200)->assertJsonStructure([
            'data' => [
                '*' => $this->getLocationStructure()
            ],
            'success',
        ]);
    }

    /**
     * Получение списка регионов.
     *
     * @return void
     */
    public function testRegions(): void
    {
        $this->json(
            'GET',
            'api/private/location/regions/RU',
            [],
            [
            ]
        )->assertStatus(200)->assertJsonStructure([
            'data' => [
                '*' => $this->getLocationStructure()
            ],
            'success',
        ]);
    }

    /**
     * Получение списка регионов с ошибкой при отсутствии страны.
     *
     * @return void
     */
    public function testRegionsNotExist(): void
    {
        $this->json(
            'GET',
            'api/private/location/regions/RU2',
            [],
            [
            ]
        )->assertStatus(404)->assertJsonStructure([
            'data',
            'success',
            'message',
        ]);
    }

    /**
     * Получить структуру данных локации.
     *
     * @return array Массив структуры данных локации.
     */
    private function getLocationStructure(): array
    {
        return [
            'code',
            'name'
        ];
    }
}
