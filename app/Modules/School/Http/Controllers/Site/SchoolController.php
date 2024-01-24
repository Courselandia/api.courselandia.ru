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
use App\Modules\School\Actions\Site\School\SchoolLinkAction;
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
        $action = new SchoolGetAction($id);
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
     * Получение школы.
     *
     * @param string $link Ссылка.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function link(string $link): JsonResponse
    {
        $action = new SchoolLinkAction($link);
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
        $action = new SchoolReadAction(
            $request->get('sorts', ['name' => 'ASC']),
            $request->get('offset'),
            $request->get('limit')
        );

        $data = $action->run();
        $data['success'] = true;

        return response()->json($data);
    }
}
