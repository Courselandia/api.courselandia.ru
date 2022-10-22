<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Http\Controllers\Site;

use Auth;
use Log;
use Exception;

use App\Models\Exceptions\InvalidCodeException;
use App\Models\Exceptions\InvalidPasswordException;
use App\Models\Exceptions\UserExistException;
use App\Models\Exceptions\UserNotExistException;
use App\Models\Exceptions\UserVerifiedException;
use App\Models\Exceptions\ValidateException;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

use App\Modules\Access\Actions\Site\AccessCheckResetPasswordAction;
use App\Modules\Access\Actions\Site\AccessForgetAction;
use App\Modules\Access\Actions\Site\AccessResetAction;
use App\Modules\Access\Actions\Site\AccessSendEmailVerificationAction;
use App\Modules\Access\Actions\Site\AccessSignInAction;
use App\Modules\Access\Actions\Site\AccessSignUpAction;
use App\Modules\Access\Actions\Site\AccessSocialAction;
use App\Modules\Access\Actions\Site\AccessUpdateAction;
use App\Modules\Access\Actions\Site\AccessVerifyAction;
use App\Modules\Access\Actions\Site\AccessPasswordAction;

use App\Modules\Access\Http\Requests\Site\AccessForgetRequest;
use App\Modules\Access\Http\Requests\Site\AccessPasswordRequest;
use App\Modules\Access\Http\Requests\Site\AccessResetCheckRequest;
use App\Modules\Access\Http\Requests\Site\AccessResetRequest;
use App\Modules\Access\Http\Requests\Site\AccessSignInRequest;
use App\Modules\Access\Http\Requests\Site\AccessSignUpRequest;
use App\Modules\Access\Http\Requests\Site\AccessSocialRequest;
use App\Modules\Access\Http\Requests\Site\AccessVerifiedRequest;

use App\Models\Exceptions\InvalidFormatException;
use App\Models\Exceptions\RecordNotExistException;
use App\Models\Exceptions\ParameterInvalidException;
use ReflectionException;

/**
 * Класс контроллер для авторизации и аутентификации.
 */
class AccessController extends Controller
{
    /**
     * Регистрация или вход через социальную сеть.
     *
     * @param  AccessSocialRequest  $request  Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function social(AccessSocialRequest $request): JsonResponse
    {
        try {
            $action = app(AccessSocialAction::class);
            $action->uid = $request->get('uid');
            $action->social = $request->get('social');
            $action->login = $request->get('login');
            $action->first_name = $request->get('first_name');
            $action->second_name = $request->get('second_name');
            $action->verify = false;

            $data = $action->run();

            Log::info(trans('access::http.controllers.site.accessController.social.log'), [
                'module' => 'Access',
                'type' => 'log in'
            ]);

            $data = [
                'success' => true,
                'data' => $data
            ];

            return response()->json($data);
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

    /**
     * Регистрация пользователя.
     *
     * @param  AccessSignUpRequest  $request  Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function signUp(AccessSignUpRequest $request): JsonResponse
    {
        try {
            $action = app(AccessSignUpAction::class);
            $action->login = $request->get('login');
            $action->password = $request->get('password');
            $action->first_name = $request->get('first_name');
            $action->second_name = $request->get('second_name');
            $action->phone = $request->get('phone');
            $action->verify = true;

            $data = $action->run();

            Log::info(trans('access::http.controllers.site.accessController.signUp.log'), [
                'module' => 'Access',
                'type' => 'create'
            ]);

            $data = [
                'success' => true,
                'data' => $data
            ];

            return response()->json($data);
        } catch (UserExistException|ValidateException $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage()
            ])->setStatusCode(400);
        }
    }

    /**
     * Авторизация пользователя.
     *
     * @param  AccessSignInRequest  $request  Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function signIn(AccessSignInRequest $request): JsonResponse
    {
        try {
            $action = app(AccessSignInAction::class);
            $action->login = $request->get('login');
            $action->password = $request->get('password');
            $action->remember = $request->get('remember');

            $data = $action->run();

            Log::info(trans('access::http.controllers.site.accessController.signIn.log'), [
                'module' => 'Access',
                'type' => 'sign in'
            ]);

            $data = [
                'success' => true,
                'data' => $data
            ];

            return response()->json($data);
        } catch (UserNotExistException|InvalidPasswordException $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage(),
            ])->setStatusCode(401);
        }
    }

    /**
     * Верификация пользователя.
     *
     * @param  int|string  $id  ID пользователя.
     * @param  AccessVerifiedRequest  $request  Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function toVerify(int|string $id, AccessVerifiedRequest $request): JsonResponse
    {
        try {
            $action = app(AccessVerifyAction::class);
            $action->id = $id;
            $action->code = $request->get('code');

            $data = $action->run();

            Log::info(trans('access::http.controllers.site.accessController.verified.log'), [
                'module' => 'Access',
                'type' => 'update'
            ]);

            $data = [
                'success' => true,
                'data' => $data
            ];

            return response()->json($data);
        } catch (InvalidCodeException $error) {
            $data = [
                'success' => false,
                'message' => $error->getMessage()
            ];

            return response()->json($data)->setStatusCode(401);
        } catch (UserVerifiedException $error) {
            $data = [
                'success' => false,
                'message' => $error->getMessage()
            ];

            return response()->json($data)->setStatusCode(400);
        }
    }

    /**
     * Отправка e-mail сообщения на верификацию.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws UserNotExistException|ParameterInvalidException|ReflectionException
     */
    public function verify(): JsonResponse
    {
        try {
            $action = app(AccessSendEmailVerificationAction::class);
            $action->login = Auth::getUser()->login;

            $result = $action->run();

            Log::info(trans('access::http.controllers.site.accessController.verify.log'), [
                'module' => 'Access',
                'type' => 'update'
            ]);

            $data = [
                'success' => $result,
            ];

            return response()->json($data)->setStatusCode($data['success'] === true ? 200 : 400);
        } catch (UserVerifiedException $error)
        {
            $data = [
                'success' => false,
                'message' => $error->getMessage()
            ];

            return response()->json($data)->setStatusCode(400);
        }
    }

    /**
     * Отправка e-mail для восстановления пароля.
     *
     * @param  AccessForgetRequest  $request  Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws Exception
     */
    public function forget(AccessForgetRequest $request): JsonResponse
    {
        try {
            $action = app(AccessForgetAction::class);
            $action->login = $request->get('login');

            $result = $action->run();

            Log::info(trans('access::http.controllers.site.accessController.forget.log'), [
                'module' => 'Access',
                'type' => 'email'
            ]);

            $data = [
                'success' => $result
            ];

            return response()->json($data);
        } catch (UserNotExistException $error) {
            $data = [
                'success' => false,
                'message' => $error->getMessage()
            ];

            return response()->json($data)->setStatusCode(400);
        }
    }

    /**
     * Проверка возможности сбить пароль.
     *
     * @param  int|string  $id  ID пользователя.
     * @param  AccessResetCheckRequest  $request  Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws UserNotExistException
     * @throws ParameterInvalidException
     * @throws UserNotExistException
     */
    public function resetCheck(int|string $id, AccessResetCheckRequest $request): JsonResponse
    {
        try {
            $action = app(AccessCheckResetPasswordAction::class);
            $action->id = $id;
            $action->code = $request->get('code');

            $status = $action->run();

            $data = [
                'success' => $status
            ];

            return response()->json($data);
        } catch (InvalidCodeException $error) {
            $data = [
                'success' => false,
                'message' => $error->getMessage()
            ];

            return response()->json($data)->setStatusCode(400);
        }
    }

    /**
     * Установка нового пароля.
     *
     * @param  int|string  $id  ID пользователя.
     * @param  AccessResetRequest  $request  Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException
     */
    public function reset(int|string $id, AccessResetRequest $request): JsonResponse
    {
        try {
            $action = app(AccessResetAction::class);
            $action->id = $id;
            $action->code = $request->get('code');
            $action->password = $request->get('password');

            $status = $action->run();

            $data = [
                'success' => $status
            ];

            return response()->json($data);
        } catch (InvalidCodeException|RecordNotExistException|UserNotExistException $error) {
            $data = [
                'success' => false,
                'message' => $error->getMessage()
            ];

            return response()->json($data)->setStatusCode(400);
        }
    }

    /**
     * Обновление данных.
     *
     * @param  Request  $request  Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function update(Request $request): JsonResponse
    {
        $action = app(AccessUpdateAction::class);
        $action->id = Auth::getUser()->id;
        $action->first_name = $request->get('first_name');
        $action->second_name = $request->get('second_name');
        $action->phone = $request->get('phone');

        $data = $action->run();

        Log::info(trans('access::http.controllers.site.accessController.update.log'), [
            'module' => 'User',
            'login' => Auth::getUser()->login,
            'type' => 'update'
        ]);

        $data = [
            'success' => true,
            'data' => $data
        ];

        return response()->json($data);
    }

    /**
     * Изменение пароля.
     *
     * @param  AccessPasswordRequest  $request  Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException|ReflectionException
     */
    public function password(AccessPasswordRequest $request): JsonResponse
    {
        try {
            $action = app(AccessPasswordAction::class);
            $action->id = Auth::getUser()->id;
            $action->password_current = $request->get('password_current');
            $action->password = $request->get('password');

            $status = $action->run();

            Log::info(trans('access::http.controllers.site.accessController.password.log'), [
                'module' => 'User',
                'login' => Auth::getUser()->login,
                'type' => 'update'
            ]);

            $data = [
                'success' => $status
            ];

            return response()->json($data)->setStatusCode($data['success'] === true ? 200 : 400);
        } catch (InvalidPasswordException|RecordNotExistException|UserNotExistException $error) {
            $data = [
                'success' => false,
                'message' => $error->getMessage()
            ];

            return response()->json($data)->setStatusCode(400);
        }
    }
}
