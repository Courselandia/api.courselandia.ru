<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Http\Controllers\Admin;

use Log;
use Auth;

use App\Modules\User\Http\Requests\Admin\Config\UserConfigUpdateRequest;

use App\Modules\User\Actions\Admin\UserConfig\UserConfigGetAction;
use App\Modules\User\Actions\Admin\UserConfig\UserConfigUpdateAction;

use App\Models\Exceptions\RecordNotExistException;
use App\Models\Exceptions\UserNotExistException;
use App\Models\Exceptions\ParameterInvalidException;

use Illuminate\Routing\Controller;
use Illuminate\Http\JsonResponse;

/**
 * Класс контроллер для работы с конфигурациями пользователя.
 */
class UserConfigController extends Controller
{
    /**
     * Получение конфигурации.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function get(): JsonResponse
    {
        try {
            $action = app(UserConfigGetAction::class);
            $action->id = Auth::getUser()->id;
            $user = $action->run();

            $data = [
                'success' => true,
                'data' => $user
            ];

            return response()->json($data);
        } catch (UserNotExistException|UserNotExistException $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage()
            ])->setStatusCode(404);
        }
    }

    /**
     * Обновление данных.
     *
     * @param  UserConfigUpdateRequest  $request  Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException
     */
    public function update(UserConfigUpdateRequest $request): JsonResponse
    {
        try {
            $action = app(UserConfigUpdateAction::class);
            $action->id = Auth::getUser()->id;
            $action->data = json_decode($request->get('configs'), true);

            $data = $action->run();

            Log::info(trans('access::http.controllers.admin.userConfigController.update.log'), [
                'module' => 'User',
                'login' => Auth::getUser()->login,
                'type' => 'update'
            ]);

            $data = [
                'success' => true,
                'data' => $data,
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
