<?php
/**
 * Модуль Публикации.
 * Этот модуль содержит все классы для работы с публикациями.
 *
 * @package App\Modules\Publication
 */

namespace App\Modules\Publication\Http\Controllers\Site;

use App\Modules\Publication\Actions\Site\PublicationReadAction;
use App\Modules\Publication\Http\Requests\Site\PublicationGetRequest;
use App\Modules\Publication\Http\Requests\Site\PublicationReadRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

/**
 * Класс контроллер для работы с публикациями.
 */
class PublicationController extends Controller
{
    /**
     * Чтение публикаций.
     *
     * @param  PublicationReadRequest  $request  Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function read(PublicationReadRequest $request): JsonResponse
    {
        $action = app(PublicationReadAction::class);
        $action->year = $request->get('year');
        $action->limit = $request->get('limit');
        $action->page = $request->get('page');

        $data = $action->run();

        $data = [
            'data' => $data,
            'success' => true
        ];

        return response()->json($data);
    }

    /**
     * Получение публикаций.
     *
     * @param  PublicationGetRequest  $request  Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function get(PublicationGetRequest $request): JsonResponse
    {
        $action = app(PublicationReadAction::class);
        $action->id = $request->get('id');
        $action->link = $request->get('link');

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
