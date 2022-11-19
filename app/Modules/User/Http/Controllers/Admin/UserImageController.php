<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Http\Controllers\Admin;

use App\Models\Exceptions\ParameterInvalidException;
use Log;
use Auth;

use Illuminate\Routing\Controller;
use Illuminate\Http\JsonResponse;

use App\Modules\User\Actions\Admin\UserImage\UserImageUpdateAction;
use App\Modules\User\Actions\Admin\UserImage\UserImageDestroyAction;

use App\Models\Exceptions\RecordExistException;
use App\Models\Exceptions\RecordNotExistException;
use App\Models\Exceptions\UserNotExistException;
use App\Models\Exceptions\ValidateException;

use App\Modules\User\Http\Requests\Admin\UserImage\UserImageUpdateRequest;
use ReflectionException;

/**
 * Класс контроллер для работы с изображениями пользователя в административной системе.
 */
class UserImageController extends Controller
{
    /**
     * Обновление данных.
     *
     * @param  int|string  $id  ID пользователя.
     * @param  UserImageUpdateRequest  $request  Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException|ParameterInvalidException
     */
    public function update(int|string $id, UserImageUpdateRequest $request): JsonResponse
    {
        try {
            $action = app(UserImageUpdateAction::class);
            $action->id = $id;
            $action->image = $request->file('image');

            $user = $action->run();

            Log::info(trans('access::http.controllers.admin.userImageController.update.log'), [
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
     * Удаление данных.
     *
     * @param  int|string  $id  ID пользователя.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException|ParameterInvalidException|ReflectionException
     */
    public function destroy(int|string $id): JsonResponse
    {
        try {
            $action = app(UserImageDestroyAction::class);
            $action->id = $id;
            $user = $action->run();

            Log::info(trans('access::http.controllers.admin.userImageController.destroy.log'), [
                'module' => 'User',
                'login' => Auth::getUser()->login,
                'type' => 'destroy'
            ]);

            $data = [
                'success' => true,
                'data' => $user
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
