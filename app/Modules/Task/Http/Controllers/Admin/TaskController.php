<?php
/**
 * Модуль Менеджер Заданий.
 * Этот модуль содержит все классы для работы с заданиями.
 *
 * @package App\Modules\Task
 */

namespace App\Modules\Task\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use ReflectionException;
use App\Modules\Task\Actions\Admin\TaskReadAction;
use App\Modules\Task\Http\Requests\Admin\TaskReadRequest;

/**
 * Класс контроллер для работы с заданиями в административной части.
 */
class TaskController extends Controller
{
    /**
     * Чтение данных.
     *
     * @param TaskReadRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ReflectionException
     */
    public function read(TaskReadRequest $request): JsonResponse
    {
        $action = new TaskReadAction(
            $request->get('sorts'),
            $request->get('filters'),
            $request->get('offset'),
            $request->get('limit'),
        );

        $data = $action->run();
        $data['success'] = true;

        return response()->json($data);
    }
}
