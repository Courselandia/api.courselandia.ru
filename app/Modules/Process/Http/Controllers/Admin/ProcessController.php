<?php
/**
 * Модуль Как проходит обучение.
 * Этот модуль содержит все классы для работы с объяснением как проходит обучение.
 *
 * @package App\Modules\Process
 */

namespace App\Modules\Process\Http\Controllers\Admin;

use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordExistException;
use App\Models\Exceptions\RecordNotExistException;
use App\Models\Exceptions\ValidateException;
use App\Modules\Process\Actions\Admin\ProcessCreateAction;
use App\Modules\Process\Actions\Admin\ProcessDestroyAction;
use App\Modules\Process\Actions\Admin\ProcessGetAction;
use App\Modules\Process\Actions\Admin\ProcessReadAction;
use App\Modules\Process\Actions\Admin\ProcessUpdateAction;
use App\Modules\Process\Actions\Admin\ProcessUpdateStatusAction;
use App\Modules\Process\Http\Requests\Admin\ProcessCreateRequest;
use App\Modules\Process\Http\Requests\Admin\ProcessDestroyRequest;
use App\Modules\Process\Http\Requests\Admin\ProcessReadRequest;
use App\Modules\Process\Http\Requests\Admin\ProcessUpdateRequest;
use App\Modules\Process\Http\Requests\Admin\ProcessUpdateStatusRequest;
use Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Log;
use ReflectionException;

/**
 * Класс контроллер для работы с объяснениями как проходит обучение в административной части.
 */
class ProcessController extends Controller
{
    /**
     * Получение объяснения как проходит обучение.
     *
     * @param int|string $id ID объяснения как проходит обучение.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException
     */
    public function get(int|string $id): JsonResponse
    {
        $action = app(ProcessGetAction::class);
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
     * @param ProcessReadRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException|ReflectionException
     */
    public function read(ProcessReadRequest $request): JsonResponse
    {
        $action = app(ProcessReadAction::class);
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
     * @param ProcessCreateRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException
     */
    public function create(ProcessCreateRequest $request): JsonResponse
    {
        try {
            $action = app(ProcessCreateAction::class);
            $action->name = $request->get('name');
            $action->text = $request->get('text');
            $action->status = $request->get('status');

            $data = $action->run();

            Log::info(
                trans('process::http.controllers.admin.processController.create.log'),
                [
                    'module' => 'Process',
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
     * @param int|string $id ID объяснения как проходит обучение.
     * @param ProcessUpdateRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException
     */
    public function update(int|string $id, ProcessUpdateRequest $request): JsonResponse
    {
        try {
            $action = app(ProcessUpdateAction::class);
            $action->id = $id;
            $action->name = $request->get('name');
            $action->text = $request->get('text');
            $action->status = $request->get('status');
            $data = $action->run();

            Log::info(
                trans('process::http.controllers.admin.processController.update.log'),
                [
                    'module' => 'Process',
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
     * @param ProcessUpdateStatusRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException
     */
    public function updateStatus(int|string $id, ProcessUpdateStatusRequest $request): JsonResponse
    {
        try {
            $action = app(ProcessUpdateStatusAction::class);
            $action->id = $id;
            $action->status = $request->get('status');

            $data = $action->run();

            Log::info(trans('process::http.controllers.admin.processController.update.log'), [
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
        } catch (RecordNotExistException $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage()
            ])->setStatusCode(404);
        }
    }

    /**
     * Удаление данных.
     *
     * @param ProcessDestroyRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function destroy(ProcessDestroyRequest $request): JsonResponse
    {
        $action = app(ProcessDestroyAction::class);
        $action->ids = $request->get('ids');
        $action->run();

        Log::info(
            trans('process::http.controllers.admin.processController.destroy.log'),
            [
                'module' => 'Process',
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
