<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Http\Controllers\Admin;

use App\Models\Exceptions\ParameterInvalidException;
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
     * @param  int|string  $id  ID пользователя.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException
     */
    public function get(int|string $id): JsonResponse
    {
        $action = app(UserGetAction::class);
        $action->id = $id;

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
     * @param  UserReadRequest  $request  Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException|ReflectionException
     */
    public function read(UserReadRequest $request): JsonResponse
    {
        $action = app(UserReadAction::class);
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
     * @param  UserCreateRequest  $request  Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function create(UserCreateRequest $request): JsonResponse
    {
        try {
            $action = app(UserCreateAction::class);
            $action->login = $request->get('login');
            $action->password = $request->get('password');
            $action->first_name = $request->get('first_name');
            $action->second_name = $request->get('second_name');
            $action->phone = $request->get('phone');
            $action->verified = $request->get('verified', false);
            $action->two_factor = $request->get('two_factor', false);
            $action->status = $request->get('status', true);
            $action->image = ($request->hasFile('image') && $request->file('image')->isValid())
                ? $request->file('image')
                : null;
            $action->role = Role::from($request->get('role'));
            $action->invitation = $request->get('invitation', false);

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
     * @param  int|string  $id  ID пользователя.
     * @param  UserUpdateRequest  $request  Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function update(int|string $id, UserUpdateRequest $request): JsonResponse
    {
        try {
            $action = app(UserUpdateAction::class);
            $action->id = $id;
            $action->login = $request->get('login');
            $action->password = $request->get('password');
            $action->first_name = $request->get('first_name');
            $action->second_name = $request->get('second_name');
            $action->phone = $request->get('phone');
            $action->verified = $request->get('verified', false);
            $action->two_factor = $request->get('two_factor', false);
            $action->status = $request->get('status', true);
            $action->image = ($request->hasFile('image') && $request->file('image')->isValid())
                ? $request->file('image')
                : null;
            $action->role = Role::from($request->get('role'));

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
     * @param  int|string  $id  ID пользователя.
     * @param  UserUpdateStatusRequest  $request  Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException
     */
    public function updateStatus(int|string $id, UserUpdateStatusRequest $request): JsonResponse
    {
        try {
            $action = app(UserUpdateStatusAction::class);
            $action->id = $id;
            $action->status = $request->get('status');

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
     * @param  int|string  $id  ID пользователя.
     * @param  Request  $request  Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException
     */
    public function password(int|string $id, Request $request): JsonResponse
    {
        try {
            $action = app(UserPasswordAction::class);
            $action->id = $id;
            $action->password = $request->get('password');

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
     * @param  UserDestroyRequest  $request  Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function destroy(UserDestroyRequest $request): JsonResponse
    {
        $action = app(UserDestroyAction::class);
        $action->ids = $request->get('ids');
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
