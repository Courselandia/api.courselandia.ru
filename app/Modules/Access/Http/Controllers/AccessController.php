<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Http\Controllers;

use Auth;
use Illuminate\Routing\Controller;
use Illuminate\Http\JsonResponse;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Access\Actions\AccessGateAction;

/**
 * Класс контроллер для авторизации и аутентификации.
 */
class AccessController extends Controller
{
    /**
     * Получение данных авторизованного пользователя.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException
     */
    public function gate(): JsonResponse
    {
        $action = new AccessGateAction(Auth::getUser()->id);

        $data = $action->run();

        if ($data) {
            $data = [
                'success' => true,
                'data' => $data
            ];

            return response()->json($data);
        } else {
            $data = [
                'success' => false,
                'data' => null
            ];

            return response()->json($data)->setStatusCode(404);
        }
    }

    /**
     * Выход пользователя.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function logout(): JsonResponse
    {
        Auth::logout();

        return response()->json(['success' => true, 'data' => null]);
    }
}
