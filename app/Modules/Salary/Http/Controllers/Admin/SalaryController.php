<?php
/**
 * Модуль Зарплаты.
 * Этот модуль содержит все классы для работы с зарплатами.
 *
 * @package App\Modules\Salary
 */

namespace App\Modules\Salary\Http\Controllers\Admin;

use App\Modules\Salary\Data\SalaryCreate;
use App\Modules\Salary\Data\SalaryUpdate;
use App\Modules\Salary\Http\Requests\Admin\SalaryUpdateRequest;
use Auth;
use Log;
use ReflectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use App\Modules\Salary\Enums\Level;
use App\Models\Exceptions\RecordExistException;
use App\Models\Exceptions\RecordNotExistException;
use App\Models\Exceptions\ValidateException;
use App\Modules\Salary\Actions\Admin\SalaryCreateAction;
use App\Modules\Salary\Actions\Admin\SalaryDestroyAction;
use App\Modules\Salary\Actions\Admin\SalaryGetAction;
use App\Modules\Salary\Actions\Admin\SalaryReadAction;
use App\Modules\Salary\Actions\Admin\SalaryUpdateAction;
use App\Modules\Salary\Actions\Admin\SalaryUpdateStatusAction;
use App\Modules\Salary\Http\Requests\Admin\SalaryCreateRequest;
use App\Modules\Salary\Http\Requests\Admin\SalaryDestroyRequest;
use App\Modules\Salary\Http\Requests\Admin\SalaryReadRequest;
use App\Modules\Salary\Http\Requests\Admin\SalaryUpdateStatusRequest;

/**
 * Класс контроллер для работы с зарплатами в административной части.
 */
class SalaryController extends Controller
{
    /**
     * Получение зарплаты.
     *
     * @param int|string $id ID зарплаты.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function get(int|string $id): JsonResponse
    {
        $action = new SalaryGetAction($id);
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
     * @param SalaryReadRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ReflectionException
     */
    public function read(SalaryReadRequest $request): JsonResponse
    {
        $action = new SalaryReadAction(
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
     * @param SalaryCreateRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function create(SalaryCreateRequest $request): JsonResponse
    {
        try {
            $data = SalaryCreate::from([
                ...$request->all(),
                'level' => Level::from($request->get('level')),
            ]);
            $action = new SalaryCreateAction($data);
            $data = $action->run();

            Log::info(
                trans('salary::http.controllers.admin.salaryController.create.log'),
                [
                    'module' => 'Salary',
                    'login' => Auth::getUser()->login,
                    'type' => 'create'
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
        }
    }

    /**
     * Обновление данных.
     *
     * @param int|string $id ID зарплаты.
     * @param SalaryUpdateRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function update(int|string $id, SalaryUpdateRequest $request): JsonResponse
    {
        try {
            $action = new SalaryUpdateAction(SalaryUpdate::from([
                'id' => $id,
                'level' => Level::from($request->get('level')),
                ...$request->all(),
            ]));
            $data = $action->run();

            Log::info(
                trans('salary::http.controllers.admin.salaryController.update.log'),
                [
                    'module' => 'Salary',
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
     * @param SalaryUpdateStatusRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function updateStatus(int|string $id, SalaryUpdateStatusRequest $request): JsonResponse
    {
        try {
            $action = new SalaryUpdateStatusAction($id, $request->get('status'));
            $data = $action->run();

            Log::info(trans('salary::http.controllers.admin.salaryController.update.log'), [
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
     * @param SalaryDestroyRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function destroy(SalaryDestroyRequest $request): JsonResponse
    {
        $action = new SalaryDestroyAction($request->get('ids'));
        $action->run();

        Log::info(
            trans('salary::http.controllers.admin.salaryController.destroy.log'),
            [
                'module' => 'Salary',
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
