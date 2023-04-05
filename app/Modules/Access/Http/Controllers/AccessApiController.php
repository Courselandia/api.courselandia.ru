<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Http\Controllers;

use App\Models\Exceptions\ValidateException;
use Illuminate\Routing\Controller;
use Illuminate\Http\JsonResponse;
use App\Models\Exceptions\InvalidPasswordException;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordNotExistException;
use App\Models\Exceptions\UserNotExistException;
use App\Models\Exceptions\InvalidFormatException;
use App\Modules\Access\Http\Requests\AccessApiTokenRequest;
use App\Modules\Access\Http\Requests\AccessApiRefreshRequest;
use App\Modules\Access\Actions\AccessApiTokenAction;
use App\Modules\Access\Actions\AccessApiRefreshAction;
use App\Modules\Access\Http\Requests\AccessApiClientRequest;
use App\Modules\Access\Actions\AccessApiClientAction;
use ReflectionException;

/**
 * Класс контроллер для генерации ключей доступа к API.
 */
class AccessApiController extends Controller
{
    /**
     * Генерация клиента.
     *
     * @param  AccessApiClientRequest  $request  Запрос на генерацию API клиента.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException|ReflectionException
     */
    public function client(AccessApiClientRequest $request): JsonResponse
    {
        try {
            $action = app(AccessApiClientAction::class);
            $action->login = $request->post('login');
            $action->password = $request->post('password');
            $action->remember = $request->post('remember', false);

            $accessApiClientEntity = $action->run();

            return response()->json([
                'success' => true,
                'data' => $accessApiClientEntity,
            ]);
        } catch (UserNotExistException|InvalidPasswordException $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage()
            ])->setStatusCode(401);
        }
    }

    /**
     * Генерация токена.
     *
     * @param  AccessApiTokenRequest  $request  Запрос на генерацию токена.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException|ReflectionException
     */
    public function token(AccessApiTokenRequest $request): JsonResponse
    {
        try {
            $action = app(AccessApiTokenAction::class);
            $action->secret = $request->post('secret');
            $action->remember = $request->post('remember', false);

            $accessApiTokenEntity = $action->run();

            return response()->json([
                'success' => true,
                'data' => $accessApiTokenEntity,
            ]);
        } catch (InvalidFormatException|RecordNotExistException $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage()
            ])->setStatusCode(401);
        } catch (ValidateException $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage()
            ])->setStatusCode(400);
        }
    }

    /**
     * Генерация токена обновления.
     *
     * @param  AccessApiRefreshRequest  $request  Запрос на обновление токена.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException|ReflectionException
     */
    public function refresh(AccessApiRefreshRequest $request): JsonResponse
    {
        try {
            $action = app(AccessApiRefreshAction::class);
            $action->refreshToken = $request->post('refreshToken');
            $action->remember = $request->post('remember', false);

            $data = $action->run();

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (InvalidFormatException $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage()
            ])->setStatusCode(401);
        } catch (ValidateException $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage()
            ])->setStatusCode(400);
        }
    }
}
