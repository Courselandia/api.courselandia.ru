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
use App\Models\Exceptions\ParameterInvalidException;
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
     * @throws ParameterInvalidException|ReflectionException
     */
    public function read(TaskReadRequest $request): JsonResponse
    {
        $action = app(TaskReadAction::class);
        $action->sorts = $request->get('sorts');
        $action->filters = $request->get('filters');
        $action->offset = $request->get('offset');
        $action->limit = $request->get('limit');

        $data = $action->run();

        $data['success'] = true;

        return response()->json($data);
    }
}
