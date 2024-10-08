<?php
/**
 * Модуль Логирование.
 * Этот модуль содержит все классы для работы с логированием.
 *
 * @package App\Modules\Log
 */

namespace App\Modules\Log\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use App\Modules\Log\Actions\Admin\LogDestroyAction;
use App\Modules\Log\Actions\Admin\LogReadAction;
use App\Modules\Log\Actions\Admin\LogGetAction;
use App\Modules\Log\Http\Requests\Admin\LogReadRequest;
use App\Modules\Log\Http\Requests\Admin\LogDestroyRequest;

/**
 * Класс контроллер для работы с логами в административной системе.
 */
class LogController extends Controller
{
    /**
     * Получение лога.
     *
     * @param string|int $id ID лога.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function get(string|int $id): JsonResponse
    {
        $action = app(LogGetAction::class, ['id' => $id]);
        $data = $action->run();

        if ($data) {
            $data = [
                'data' => $data,
                'success' => true,
            ];

            return response()->json($data);
        }

        $data = [
            'data' => null,
            'success' => false,
        ];

        return response()
            ->json($data)
            ->setStatusCode(404);
    }

    /**
     * Чтение данных.
     *
     * @param LogReadRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function read(LogReadRequest $request): JsonResponse
    {
        $action = app(LogReadAction::class, [
            'sorts' => $request->get('sorts'),
            'filters' => $request->get('filters'),
            'offset' => $request->get('offset'),
            'limit' => $request->get('limit'),
        ]);

        $data = $action->run();
        $data['success'] = true;

        return response()
            ->json($data)
            ->setStatusCode(200);
    }

    /**
     * Удаление данных.
     *
     * @param LogDestroyRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function destroy(LogDestroyRequest $request): JsonResponse
    {
        $action = app(LogDestroyAction::class, ['ids' => $request->get('ids')]);
        $action->run();

        $data = [
            'success' => true,
        ];

        return response()->json($data);
    }
}
