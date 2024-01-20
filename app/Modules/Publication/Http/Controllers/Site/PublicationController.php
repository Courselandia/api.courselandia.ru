<?php
/**
 * Модуль Публикации.
 * Этот модуль содержит все классы для работы с публикациями.
 *
 * @package App\Modules\Publication
 */

namespace App\Modules\Publication\Http\Controllers\Site;

use App\Modules\Publication\Values\PublicationYear;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use App\Modules\Publication\Actions\Site\PublicationReadAction;
use App\Modules\Publication\Http\Requests\Site\PublicationGetRequest;
use App\Modules\Publication\Http\Requests\Site\PublicationReadRequest;
use App\Modules\Publication\Data\Actions\Site\PublicationRead;

/**
 * Класс контроллер для работы с публикациями.
 */
class PublicationController extends Controller
{
    /**
     * Чтение публикаций.
     *
     * @param PublicationReadRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function read(PublicationReadRequest $request): JsonResponse
    {
        $action = new PublicationReadAction(PublicationRead::from([
            'year' => $request->get('year'),
            'limit' => $request->get('limit'),
            'offset' => $request->get('offset'),
        ]));

        $data = $action->run();

        $data = [
            'data' => [
                ...$data->toArray(),
                'years' => collect($data->years)->map(function ($year) {
                    /**
                     * @var PublicationYear $year
                     */
                    return [
                        'year' => $year->getYear(),
                        'current' => $year->getCurrent(),
                    ];
                })
            ],
            'success' => true
        ];

        return response()->json($data);
    }

    /**
     * Получение публикаций.
     *
     * @param PublicationGetRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function get(PublicationGetRequest $request): JsonResponse
    {
        $action = new PublicationReadAction(PublicationRead::from([
            'id' => $request->get('id'),
            'link' => $request->get('link'),
        ]));
        $data = $action->run();

        if ($data) {
            $data = [
                'data' => $data,
                'success' => true
            ];

            return response()->json($data);
        } else {
            $data = [
                'data' => null,
                'success' => false,
            ];

            return response()->json($data)->setStatusCode(404);
        }
    }
}
