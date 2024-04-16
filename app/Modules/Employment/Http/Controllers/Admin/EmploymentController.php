<?php
/**
 * Модуль Трудоустройство.
 * Этот модуль содержит все классы для работы с трудоустройствами.
 *
 * @package App\Modules\Employment
 */

namespace App\Modules\Employment\Http\Controllers\Admin;

use App\Models\Exceptions\RecordExistException;
use App\Models\Exceptions\RecordNotExistException;
use App\Models\Exceptions\ValidateException;
use App\Modules\Employment\Actions\Admin\EmploymentCreateAction;
use App\Modules\Employment\Actions\Admin\EmploymentDestroyAction;
use App\Modules\Employment\Actions\Admin\EmploymentGetAction;
use App\Modules\Employment\Actions\Admin\EmploymentReadAction;
use App\Modules\Employment\Actions\Admin\EmploymentUpdateAction;
use App\Modules\Employment\Actions\Admin\EmploymentUpdateStatusAction;
use App\Modules\Employment\Data\EmploymentCreate;
use App\Modules\Employment\Data\EmploymentUpdate;
use App\Modules\Employment\Http\Requests\Admin\EmploymentCreateRequest;
use App\Modules\Employment\Http\Requests\Admin\EmploymentDestroyRequest;
use App\Modules\Employment\Http\Requests\Admin\EmploymentReadRequest;
use App\Modules\Employment\Http\Requests\Admin\EmploymentUpdateRequest;
use App\Modules\Employment\Http\Requests\Admin\EmploymentUpdateStatusRequest;
use Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Log;
use ReflectionException;

/**
 * Класс контроллер для работы с трудоустройствами в административной части.
 */
class EmploymentController extends Controller
{
    /**
     * Получение трудоустройства.
     *
     * @param int|string $id ID трудоустройства.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function get(int|string $id): JsonResponse
    {
        $action = new EmploymentGetAction($id);
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
     * @param EmploymentReadRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ReflectionException
     */
    public function read(EmploymentReadRequest $request): JsonResponse
    {
        $action = new EmploymentReadAction(
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
     * @param EmploymentCreateRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function create(EmploymentCreateRequest $request): JsonResponse
    {
        try {
            $data = EmploymentCreate::from($request->all());
            $action = new EmploymentCreateAction($data);
            $data = $action->run();

            Log::info(
                trans('employment::http.controllers.admin.employmentController.create.log'),
                [
                    'module' => 'Employment',
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
     * @param int|string $id ID трудоустройства.
     * @param EmploymentUpdateRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function update(int|string $id, EmploymentUpdateRequest $request): JsonResponse
    {
        try {
            $data = EmploymentUpdate::from([
                ...$request->all(),
                'id' => $id,
            ]);
            $action = new EmploymentUpdateAction($data);
            $data = $action->run();

            Log::info(
                trans('employment::http.controllers.admin.employmentController.update.log'),
                [
                    'module' => 'Employment',
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
     * @param EmploymentUpdateStatusRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function updateStatus(int|string $id, EmploymentUpdateStatusRequest $request): JsonResponse
    {
        try {
            $action = new EmploymentUpdateStatusAction($id, $request->get('status'));
            $data = $action->run();

            Log::info(trans('employment::http.controllers.admin.employmentController.update.log'), [
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
     * @param EmploymentDestroyRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function destroy(EmploymentDestroyRequest $request): JsonResponse
    {
        $action = new EmploymentDestroyAction($request->get('ids'));
        $action->run();

        Log::info(
            trans('employment::http.controllers.admin.employmentController.destroy.log'),
            [
                'module' => 'Employment',
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
