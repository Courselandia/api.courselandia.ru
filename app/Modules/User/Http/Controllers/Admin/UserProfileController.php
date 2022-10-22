<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Http\Controllers\Admin;

use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\User\Actions\Admin\UserImage\UserImageUpdateAction;
use App\Modules\User\Http\Requests\Admin\Profile\UserProfileUpdateImageRequest;
use Auth;
use Log;

use App\Modules\User\Actions\Admin\User\UserPasswordAction;
use Illuminate\Http\Request;

use Illuminate\Routing\Controller;
use Illuminate\Http\JsonResponse;

use App\Models\Exceptions\RecordExistException;
use App\Models\Exceptions\RecordNotExistException;
use App\Models\Exceptions\UserNotExistException;
use App\Models\Exceptions\ValidateException;

use App\Modules\User\Actions\Admin\Profile\UserProfileUpdateAction;
use App\Modules\User\Actions\Admin\UserImage\UserImageDestroyAction;
use App\Modules\User\Http\Requests\Admin\Profile\UserProfileUpdateRequest;
use ReflectionException;

/**
 * Класс контроллер для работы с профилем пользователя.
 */
class UserProfileController extends Controller
{
    /**
     * Обновление данных.
     *
     * @param  UserProfileUpdateRequest  $request  Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function update(UserProfileUpdateRequest $request): JsonResponse
    {
        try {
            $action = app(UserProfileUpdateAction::class);
            $action->id = Auth::getUser()->id;
            $action->first_name = $request->get('first_name');
            $action->second_name = $request->get('second_name');
            $action->phone = $request->get('phone');
            $action->image = ($request->hasFile('image') && $request->file('image')->isValid()) ? $request->file('image') : null;
            $data = $action->run();

            Log::info(trans('access::http.controllers.admin.userController.update.log'), [
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
     * Обновление пароля профиля.
     *
     * @param  Request  $request  Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException|ReflectionException
     */
    public function password(Request $request): JsonResponse
    {
        try {
            $action = app(UserPasswordAction::class);
            $action->id = Auth::getUser()->id;
            $action->password = $request->get('password');
            $user = $action->run();

            Log::info(trans('access::http.controllers.admin.userController.password.log'), [
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
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException|ReflectionException
     */
    public function destroyImage(): JsonResponse
    {
        try {
            $action = app(UserImageDestroyAction::class);
            $action->id = Auth::getUser()->id;
            $data = $action->run();

            Log::info(trans('access::http.controllers.admin.userController.destroyImage.log'), [
                'module' => 'User',
                'login' => Auth::getUser()->login,
                'type' => 'destroy'
            ]);

            $data = [
                'success' => true,
                'data' => $data
            ];

            return response()->json($data);
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
     * @param  UserProfileUpdateImageRequest  $request  Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException|ReflectionException
     */
    public function updateImage(UserProfileUpdateImageRequest $request): JsonResponse
    {
        try {
            $action = app(UserImageUpdateAction::class);
            $action->id = Auth::getUser()->id;
            $action->image = ($request->hasFile('image') && $request->file('image')->isValid()) ? $request->file('image') : null;
            $data = $action->run();

            Log::info(trans('access::http.controllers.admin.userController.destroyImage.log'), [
                'module' => 'User',
                'login' => Auth::getUser()->login,
                'type' => 'destroy'
            ]);

            $data = [
                'success' => true,
                'data' => $data
            ];

            return response()->json($data);
        } catch (RecordNotExistException|UserNotExistException $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage()
            ])->setStatusCode(404);
        }
    }
}
