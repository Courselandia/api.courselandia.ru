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
use App\Modules\Process\Data\ProcessCreate;
use App\Modules\Process\Data\ProcessUpdate;
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
     */
    public function get(int|string $id): JsonResponse
    {
        $action = new ProcessGetAction($id);
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
        $action = new ProcessReadAction(
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
     * @param ProcessCreateRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function create(ProcessCreateRequest $request): JsonResponse
    {
        try {
            $data = ProcessCreate::from($request->all());
            $action = new ProcessCreateAction($data);
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
     */
    public function update(int|string $id, ProcessUpdateRequest $request): JsonResponse
    {
        try {
            $data = ProcessUpdate::from([
                'id' => $id,
                ...$request->all()
            ]);
            $action = new ProcessUpdateAction($data);
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
     */
    public function updateStatus(int|string $id, ProcessUpdateStatusRequest $request): JsonResponse
    {
        try {
            $action = new ProcessUpdateStatusAction($id, $request->get('status'));
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
        $action = new ProcessDestroyAction($request->get('ids'));
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
