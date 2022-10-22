<?php
/**
 * Модуль География.
 * Этот модуль содержит все классы для работы со странами, районами, городами и т.д.
 *
 * @package App\Modules\Location
 */

namespace App\Modules\Location\Http\Controllers;

use App\Modules\Location\Actions\Admin\LocationCountriesReadAction;
use App\Modules\Location\Actions\Admin\LocationRegionsReadAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use MenaraSolutions\Geographer\Exceptions\ObjectNotFoundException;

/**
 * Класс контроллер для работы с географическими объектами.
 */
class LocationController extends Controller
{
    /**
     * Получить все страны.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function countries(): JsonResponse
    {
        $countries = app(LocationCountriesReadAction::class)->run();

        $data = [
            'data' => $countries,
            'success' => true,
        ];

        return response()->json($data);
    }

    /**
     * Получить регионы по стране.
     *
     * @param  string  $country  Код страны.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function regions(string $country): JsonResponse
    {
        try {
            $action = app(LocationRegionsReadAction::class);
            $action->country = $country;
            $regions = $action->run();

            $data = [
                'data' => $regions,
                'success' => true,
            ];

            return response()->json($data);
        } catch (ObjectNotFoundException $error) {
            $data = [
                'data' => null,
                'success' => false,
                'message' => $error->getMessage()
            ];

            return response()
                ->json($data)
                ->setStatusCode(404);
        }
    }
}
