<?php
/**
 * Модуль Профессии.
 * Этот модуль содержит все классы для работы с профессиями.
 *
 * @package App\Modules\Profession
 */

namespace App\Modules\Profession\Http\Controllers\Admin;

use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordExistException;
use App\Models\Exceptions\RecordNotExistException;
use App\Models\Exceptions\UserNotExistException;
use App\Models\Exceptions\ValidateException;
use App\Modules\Profession\Actions\Admin\ProfessionCreateAction;
use App\Modules\Profession\Actions\Admin\ProfessionDestroyAction;
use App\Modules\Profession\Actions\Admin\ProfessionGetAction;
use App\Modules\Profession\Actions\Admin\ProfessionReadAction;
use App\Modules\Profession\Actions\Admin\ProfessionUpdateAction;
use App\Modules\Profession\Actions\Admin\ProfessionUpdateStatusAction;
use App\Modules\Profession\Http\Requests\Admin\ProfessionDestroyRequest;
use App\Modules\Profession\Http\Requests\Admin\ProfessionReadRequest;
use App\Modules\Profession\Http\Requests\Admin\ProfessionUpdateStatusRequest;
use Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Log;
use ReflectionException;

/**
 * Класс контроллер для работы с профессиями в административной части.
 */
class ProfessionController extends Controller
{
    /**
     * Получение профессии.
     *
     * @param int|string $id ID профессии.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException|ReflectionException
     */
    public function get(int|string $id): JsonResponse
    {
        $action = app(ProfessionGetAction::class);
        $action->id = $id;
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
     * @param ProfessionReadRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException|ReflectionException
     */
    public function read(ProfessionReadRequest $request): JsonResponse
    {
        $action = app(ProfessionReadAction::class);
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
     * @param Request $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws RecordNotExistException|ParameterInvalidException|ReflectionException
     */
    public function create(Request $request): JsonResponse
    {
        try {
            $action = app(ProfessionCreateAction::class);
            $action->name = $request->get('name');
            $action->header = $request->get('header');
            $action->link = $request->get('link');
            $action->text = $request->get('text');
            $action->status = $request->get('status');
            $action->title = $request->get('title');
            $action->description = $request->get('description');
            $action->keywords = $request->get('keywords');

            $data = $action->run();

            Log::info(
                trans('profession::http.controllers.admin.professionController.create.log'),
                [
                    'module' => 'Profession',
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
     * @param int|string $id ID профессии.
     * @param Request $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException|ReflectionException
     */
    public function update(int|string $id, Request $request): JsonResponse
    {
        try {
            $action = app(ProfessionUpdateAction::class);
            $action->id = $id;
            $action->name = $request->get('name');
            $action->header = $request->get('header');
            $action->link = $request->get('link');
            $action->text = $request->get('text');
            $action->status = $request->get('status');
            $action->title = $request->get('title');
            $action->description = $request->get('description');
            $action->keywords = $request->get('keywords');
            $data = $action->run();

            Log::info(
                trans('profession::http.controllers.admin.professionController.update.log'),
                [
                    'module' => 'Profession',
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
     * @param ProfessionUpdateStatusRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException|ReflectionException
     */
    public function updateStatus(int|string $id, ProfessionUpdateStatusRequest $request): JsonResponse
    {
        try {
            $action = app(ProfessionUpdateStatusAction::class);
            $action->id = $id;
            $action->status = $request->get('status');

            $data = $action->run();

            Log::info(trans('profession::http.controllers.admin.professionController.update.log'), [
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
     * Удаление данных.
     *
     * @param ProfessionDestroyRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function destroy(ProfessionDestroyRequest $request): JsonResponse
    {
        $action = app(ProfessionDestroyAction::class);
        $action->ids = json_decode($request->get('ids'), true);
        $action->run();

        Log::info(
            trans('profession::http.controllers.admin.professionController.destroy.log'),
            [
                'module' => 'Profession',
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
