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
use App\Models\Exceptions\UserNotExistException;
use App\Models\Exceptions\ValidateException;
use App\Modules\Direction\Actions\Admin\DirectionCreateAction;
use App\Modules\Direction\Actions\Admin\DirectionDestroyAction;
use App\Modules\Direction\Actions\Admin\DirectionGetAction;
use App\Modules\Direction\Actions\Admin\DirectionReadAction;
use App\Modules\Direction\Actions\Admin\DirectionUpdateAction;
use App\Modules\Direction\Actions\Admin\DirectionUpdateStatusAction;
use App\Modules\Direction\Http\Requests\Admin\DirectionDestroyRequest;
use App\Modules\Direction\Http\Requests\Admin\DirectionReadRequest;
use App\Modules\Direction\Http\Requests\Admin\DirectionUpdateStatusRequest;
use Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
     * @throws ParameterInvalidException|ReflectionException
     */
    public function get(int|string $id): JsonResponse
    {
        $action = app(DirectionGetAction::class);
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
     * @param DirectionReadRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException|ReflectionException
     */
    public function read(DirectionReadRequest $request): JsonResponse
    {
        $action = app(DirectionReadAction::class);
        $action->sorts = $request->get('sorts');
        $action->filters = $request->get('filters');
        $action->offset = $request->get('offset');
        $action->limit = $request->get('limit');

        $data = $action->run();

        $data['success'] = true;

        return response()->json($data);
    }

    /**
     * Добавление данных.
     *
     * @param Request $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws RecordNotExistException|ParameterInvalidException|ReflectionException
     */
    public function create(Request $request): JsonResponse
    {
        try {
            $action = app(DirectionCreateAction::class);
            $action->name = $request->get('name');
            $action->header = $request->get('header');
            $action->weight = $request->get('weight');
            $action->link = $request->get('link');
            $action->text = $request->get('text');
            $action->status = $request->get('status');
            $action->title = $request->get('title');
            $action->description = $request->get('description');
            $action->keywords = $request->get('keywords');

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
        } catch (ValidateException $error) {
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
     * @param Request $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException|ReflectionException
     */
    public function update(int|string $id, Request $request): JsonResponse
    {
        try {
            $action = app(DirectionUpdateAction::class);
            $action->id = $id;
            $action->name = $request->get('name');
            $action->header = $request->get('header');
            $action->weight = $request->get('weight');
            $action->link = $request->get('link');
            $action->text = $request->get('text');
            $action->status = $request->get('status');
            $action->title = $request->get('title');
            $action->description = $request->get('description');
            $action->keywords = $request->get('keywords');
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
        } catch (ValidateException|RecordExistException $error) {
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
     * @throws ParameterInvalidException|ReflectionException
     */
    public function updateStatus(int|string $id, DirectionUpdateStatusRequest $request): JsonResponse
    {
        try {
            $action = app(DirectionUpdateStatusAction::class);
            $action->id = $id;
            $action->status = $request->get('status');

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
        } catch (RecordNotExistException|UserNotExistException $error) {
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
        $action = app(DirectionDestroyAction::class);
        $action->ids = $request->get('ids');
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
