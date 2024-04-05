<?php
/**
 * Модуль Коллекций.
 * Этот модуль содержит все классы для работы с коллекциями.
 *
 * @package App\Modules\Collection
 */

namespace App\Modules\Collection\Http\Controllers\Site;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use App\Modules\Collection\Actions\Site\CollectionReadAction;
use App\Modules\Collection\Http\Requests\Site\CollectionReadRequest;
use App\Modules\Collection\Actions\Site\CollectionLinkAction;

/**
 * Класс контроллер для работы с коллекциями.
 */
class CollectionController extends Controller
{
    /**
     * Чтение коллекций.
     *
     * @param CollectionReadRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function read(CollectionReadRequest $request): JsonResponse
    {
        $action = new CollectionReadAction(
            $request->get('direction_id'),
            $request->get('offset'),
            $request->get('limit'),
        );

        $data = $action->run();

        $data = [
            'data' => $data->toArray(),
            'success' => true,
        ];

        return response()->json($data);
    }

    /**
     * Получение коллекции.
     *
     * @param string $link Ссылка.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function link(string $link): JsonResponse
    {
        $action = new CollectionLinkAction($link);
        $data = $action->run();

        if ($data) {
            $data = [
                'data' => $data,
                'success' => true,
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
