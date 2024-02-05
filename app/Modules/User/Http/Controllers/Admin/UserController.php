<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Http\Controllers\Admin;

use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\User\Data\Actions\UserCreate;
use App\Modules\User\Data\Decorators\UserUpdate;
use Auth;
use Log;
use App\Modules\User\Actions\Admin\User\UserUpdateStatusAction;
use App\Modules\User\Http\Requests\Admin\User\UserUpdateStatusRequest;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Http\JsonResponse;
use App\Modules\User\Actions\Admin\User\UserGetAction;
use App\Modules\User\Actions\Admin\User\UserReadAction;
use App\Modules\User\Actions\Admin\User\UserCreateAction;
use App\Modules\User\Actions\Admin\User\UserUpdateAction;
use App\Modules\User\Actions\Admin\User\UserPasswordAction;
use App\Modules\User\Actions\Admin\User\UserDestroyAction;
use App\Models\Exceptions\RecordExistException;
use App\Models\Exceptions\RecordNotExistException;
use App\Models\Exceptions\UserNotExistException;
use App\Models\Exceptions\ValidateException;
use App\Models\Exceptions\UserExistException;
use App\Modules\User\Http\Requests\Admin\User\UserReadRequest;
use App\Modules\User\Http\Requests\Admin\User\UserDestroyRequest;
use App\Modules\User\Http\Requests\Admin\User\UserCreateRequest;
use App\Modules\User\Http\Requests\Admin\User\UserUpdateRequest;
use App\Modules\User\Enums\Role;
use ReflectionException;

/**
 * Класс контроллер для работы с пользователями в административной системе.
 */
class UserController extends Controller
{
    /**
     * Получение пользователя.
     *
     * @param int|string $id ID пользователя.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function get(int|string $id): JsonResponse
    {
        $action = new UserGetAction($id);
        $user = $action->run();

        if ($user) {
            $data = [
                'data' => $user,
                'success' => true,
            ];

            return response()->json($data);
        } else {
            $data = [
                'data' => null,
                'success' => false,
            ];

            return response()
                ->json($data)
                ->setStatusCode(404);
        }
    }

    /**
     * Чтение данных.
     *
     * @param UserReadRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException|ReflectionException
     */
    public function read(UserReadRequest $request): JsonResponse
    {
        $action = new UserReadAction(
            $request->get('sorts'),
            $request->get('filters'),
            $request->get('offset'),
            $request->get('limit'),
        );

        $data = $action->run();
        $data['success'] = true;

        return response()->json($data);
    }

    /**
     * Добавление данных.
     *
     * @param UserCreateRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function create(UserCreateRequest $request): JsonResponse
    {
        try {
            $data = UserCreate::from([
                ...$request->toArray(),
                'verified' => $request->get('verified', false),
                'two_factor' => $request->get('two_factor', false),
                'status' => $request->get('status', true),
                'invitation' => $request->get('invitation', true),
                'image' => ($request->hasFile('image') && $request->file('image')->isValid())
                    ? $request->file('image')
                    : null,
                'role' => Role::from($request->get('role')),
            ]);
            $action = new UserCreateAction($data);
            $user = $action->run();

            Log::info(trans('user::http.controllers.admin.userController.create.log'), [
                'module' => 'User',
                'login' => Auth::getUser()->login,
                'type' => 'create'
            ]);

            $data = [
                'success' => true,
                'data' => $user
            ];

            return response()->json($data);
        } catch (ValidateException|RecordExistException|UserExistException $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage()
            ])->setStatusCode(400);
        }
    }

    /**
     * Обновление данных.
     *
     * @param int|string $id ID пользователя.
     * @param UserUpdateRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function update(int|string $id, UserUpdateRequest $request): JsonResponse
    {
        try {
            $data = UserUpdate::from([
                ...$request->all(),
                'id' => $id,
                'image' => ($request->hasFile('image') && $request->file('image')->isValid())
                    ? $request->file('image')
                    : null,
                'role' => Role::from($request->get('role')),
                'verified' => $request->get('verified', false),
                'two_factor' => $request->get('two_factor', false),
                'status' => $request->get('status', true),
            ]);
            $action = new UserUpdateAction($data);
            $user = $action->run();

            Log::info(trans('user::http.controllers.admin.userController.update.log'), [
                'module' => 'User',
                'login' => Auth::getUser()->login,
                'type' => 'update'
            ]);

            $data = [
                'success' => true,
                'data' => $user
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
     * Обновление статуса.
     *
     * @param int|string $id ID пользователя.
     * @param UserUpdateStatusRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function updateStatus(int|string $id, UserUpdateStatusRequest $request): JsonResponse
    {
        try {
            $action = new UserUpdateStatusAction($id, $request->get('status'));
            $user = $action->run();

            Log::info(trans('user::http.controllers.admin.userController.update.log'), [
                'module' => 'User',
                'login' => Auth::getUser()->login,
                'type' => 'update'
            ]);

            $data = [
                'success' => true,
                'data' => $user
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
     * Обновление пароля пользователя.
     *
     * @param int|string $id ID пользователя.
     * @param Request $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function password(int|string $id, Request $request): JsonResponse
    {
        try {
            $action = new UserPasswordAction($id, $request->get('password'));
            $user = $action->run();

            Log::info(trans('user::http.controllers.admin.userController.password.log'), [
                'module' => 'User',
                'login' => Auth::getUser()->login,
                'type' => 'password'
            ]);

            $data = [
                'success' => true,
                'data' => $user
            ];

            return response()->json($data);
        } catch (UserNotExistException|RecordNotExistException $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage()
            ])->setStatusCode(404);
        }
    }

    /**
     * Удаление данных.
     *
     * @param UserDestroyRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function destroy(UserDestroyRequest $request): JsonResponse
    {
        $action = new UserDestroyAction($request->get('ids'));
        $action->run();

        Log::info(trans('user::http.controllers.admin.userController.destroy.log'), [
            'module' => 'User',
            'login' => Auth::getUser()->login,
            'type' => 'destroy'
        ]);

        $data = [
            'success' => true,
        ];

        return response()->json($data);
    }
}
