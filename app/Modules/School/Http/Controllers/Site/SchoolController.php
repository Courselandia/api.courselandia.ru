<?php
/**
 * Модуль Школ.
 * Этот модуль содержит все классы для работы со школами.
 *
 * @package App\Modules\School
 */

namespace App\Modules\School\Http\Controllers\Site;

use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\School\Actions\Site\School\SchoolGetAction;
use App\Modules\School\Actions\Site\School\SchoolReadAction;
use App\Modules\School\Http\Requests\Site\School\SchoolReadRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use ReflectionException;

/**
 * Класс контроллер для работы со школами в публичной части.
 */
class SchoolController extends Controller
{
    /**
     * Получение школы.
     *
     * @param int|string $id ID школы.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException
     */
    public function get(int|string $id): JsonResponse
    {
        $action = app(SchoolGetAction::class);
        $action->id = $id;
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

    /**
     * Чтение данных.
     *
     * @param SchoolReadRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException|ReflectionException
     */
    public function read(SchoolReadRequest $request): JsonResponse
    {
        $action = app(SchoolReadAction::class);
        $action->sorts = $request->get('sorts');
        $action->offset = $request->get('offset');
        $action->limit = $request->get('limit');

        $data = $action->run();

        $data['success'] = true;

        return response()->json($data);
    }
}
