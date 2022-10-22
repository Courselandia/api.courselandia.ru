<?php
/**
 * Модуль Логирование.
 * Этот модуль содержит все классы для работы с логированием.
 *
 * @package App\Modules\Log
 */

namespace App\Modules\Log\Http\Controllers\Admin;

use ReflectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

use App\Models\Exceptions\ParameterInvalidException;
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
     * @param  string|int  $id  ID лога.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException|ReflectionException
     */
    public function get(string|int $id): JsonResponse
    {
        $action = app(LogGetAction::class);
        $action->id = $id;

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
     * @param  LogReadRequest  $request  Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function read(LogReadRequest $request): JsonResponse
    {
        $action = app(LogReadAction::class);

        $action->search = $request->get('search');
        $action->filters = $request->get('filters');
        $action->sorts = $request->get('sorts');
        $action->offset = $request->get('offset');
        $action->limit = $request->get('limit');

        $data = $action->run();

        $data['success'] = true;

        return response()
            ->json($data)
            ->setStatusCode(200);
    }

    /**
     * Удаление данных.
     *
     * @param  LogDestroyRequest  $request  Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function destroy(LogDestroyRequest $request): JsonResponse
    {
        $action = app(LogDestroyAction::class);
        $action->ids = json_decode($request->get('ids'), true);
        $action->run();

        $data = [
            'success' => true,
        ];

        return response()->json($data);
    }
}
