<?php
/**
 * Модуль Направления.
 * Этот модуль содержит все классы для работы с направлениями.
 *
 * @package App\Modules\Direction
 */

namespace App\Modules\Direction\Http\Controllers\Admin;

use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordExistException;
use App\Models\Exceptions\RecordNotExistException;
use App\Models\Exceptions\ValidateException;
use App\Modules\Direction\Actions\Admin\DirectionCreateAction;
use App\Modules\Direction\Actions\Admin\DirectionDestroyAction;
use App\Modules\Direction\Actions\Admin\DirectionGetAction;
use App\Modules\Direction\Actions\Admin\DirectionReadAction;
use App\Modules\Direction\Actions\Admin\DirectionUpdateAction;
use App\Modules\Direction\Actions\Admin\DirectionUpdateStatusAction;
use App\Modules\Direction\Data\DirectionCreate;
use App\Modules\Direction\Data\DirectionUpdate;
use App\Modules\Direction\Http\Requests\Admin\DirectionCreateRequest;
use App\Modules\Direction\Http\Requests\Admin\DirectionDestroyRequest;
use App\Modules\Direction\Http\Requests\Admin\DirectionReadRequest;
use App\Modules\Direction\Http\Requests\Admin\DirectionUpdateRequest;
use App\Modules\Direction\Http\Requests\Admin\DirectionUpdateStatusRequest;
use App\Modules\Metatag\Template\TemplateException;
use Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Log;
use ReflectionException;

/**
 * Класс контроллер для работы с направлениями в административной части.
 */
class DirectionController extends Controller
{
    /**
     * Получение направления.
     *
     * @param int|string $id ID направления.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function get(int|string $id): JsonResponse
    {
        $action = new DirectionGetAction($id);
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
     * @param DirectionReadRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException|ReflectionException
     */
    public function read(DirectionReadRequest $request): JsonResponse
    {
        $action = new DirectionReadAction(
            $request->get('sorts'),
            $request->get('filters'),
            $request->get('offset'),
            $request->get('limit')
        );

        $data = $action->run();

        $data['success'] = true;

        return response()->json($data);
    }

    /**
     * Добавление данных.
     *
     * @param DirectionCreateRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException
     */
    public function create(DirectionCreateRequest $request): JsonResponse
    {
        try {
            $data = DirectionCreate::from($request->all());
            $action = new DirectionCreateAction($data);
            $data = $action->run();

            Log::info(
                trans('direction::http.controllers.admin.directionController.create.log'),
                [
                    'module' => 'Direction',
                    'login' => Auth::getUser()->login,
                    'type' => 'create'
                ]
            );

            $data = [
                'data' => $data,
                'success' => true
            ];

            return response()->json($data);
        } catch (ValidateException|TemplateException $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage()
            ])->setStatusCode(400);
        } catch (RecordExistException $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage()
            ])->setStatusCode(404);
        }
    }

    /**
     * Обновление данных.
     *
     * @param int|string $id ID направления.
     * @param DirectionUpdateRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException
     */
    public function update(int|string $id, DirectionUpdateRequest $request): JsonResponse
    {
        try {
            $data = DirectionUpdate::from([
                ...$request->all(),
                'id' => $id,
            ]);
            $action = new DirectionUpdateAction($data);
            $data = $action->run();

            Log::info(
                trans('direction::http.controllers.admin.directionController.update.log'),
                [
                    'module' => 'Direction',
                    'login' => Auth::getUser()->login,
                    'type' => 'update'
                ]
            );

            $data = [
                'data' => $data,
                'success' => true
            ];

            return response()->json($data);
        } catch (ValidateException|RecordExistException|TemplateException $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage()
            ])->setStatusCode(400);
        } catch (RecordNotExistException $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage()
            ])->setStatusCode(404);
        }
    }

    /**
     * Обновление статуса.
     *
     * @param int|string $id ID пользователя.
     * @param DirectionUpdateStatusRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function updateStatus(int|string $id, DirectionUpdateStatusRequest $request): JsonResponse
    {
        try {
            $action = new DirectionUpdateStatusAction($id, $request->get('status'));
            $data = $action->run();

            Log::info(trans('direction::http.controllers.admin.directionController.update.log'), [
                'module' => 'User',
                'login' => Auth::getUser()->login,
                'type' => 'update'
            ]);

            $data = [
                'success' => true,
                'data' => $data
            ];

            return response()->json($data);
        } catch (ValidateException|RecordExistException $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage()
            ])->setStatusCode(400);
        } catch (RecordNotExistException|RecordNotExistException $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage()
            ])->setStatusCode(404);
        }
    }

    /**
     * Удаление данных.
     *
     * @param DirectionDestroyRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function destroy(DirectionDestroyRequest $request): JsonResponse
    {
        $action = new DirectionDestroyAction($request->get('ids'));
        $action->run();

        Log::info(
            trans('direction::http.controllers.admin.directionController.destroy.log'),
            [
                'module' => 'Direction',
                'login' => Auth::getUser()->login,
                'type' => 'destroy'
            ]
        );

        $data = [
            'success' => true
        ];

        return response()->json($data);
    }
}
