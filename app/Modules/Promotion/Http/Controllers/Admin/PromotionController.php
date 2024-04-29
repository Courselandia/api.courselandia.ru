<?php
/**
 * Модуль Промоакций.
 * Этот модуль содержит все классы для работы с промоакциями.
 *
 * @package App\Modules\Promotion
 */

namespace App\Modules\Promotion\Http\Controllers\Admin;

use Log;
use Auth;
use Config;
use Throwable;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use ReflectionException;
use App\Models\Exceptions\RecordExistException;
use App\Models\Exceptions\RecordNotExistException;
use App\Models\Exceptions\ValidateException;
use App\Modules\Metatag\Template\TemplateException;
use App\Modules\Promotion\Actions\Admin\PromotionCreateAction;
use App\Modules\Promotion\Actions\Admin\PromotionDestroyAction;
use App\Modules\Promotion\Actions\Admin\PromotionGetAction;
use App\Modules\Promotion\Actions\Admin\PromotionReadAction;
use App\Modules\Promotion\Actions\Admin\PromotionUpdateAction;
use App\Modules\Promotion\Actions\Admin\PromotionUpdateStatusAction;
use App\Modules\Promotion\Data\PromotionCreate;
use App\Modules\Promotion\Data\PromotionUpdate;
use App\Modules\Promotion\Http\Requests\Admin\PromotionCreateRequest;
use App\Modules\Promotion\Http\Requests\Admin\PromotionDestroyRequest;
use App\Modules\Promotion\Http\Requests\Admin\PromotionReadRequest;
use App\Modules\Promotion\Http\Requests\Admin\PromotionUpdateRequest;
use App\Modules\Promotion\Http\Requests\Admin\PromotionUpdateStatusRequest;

/**
 * Класс контроллер для работы с промоакций в административной части.
 */
class PromotionController extends Controller
{
    /**
     * Получение промоакций.
     *
     * @param int|string $id ID промоакции.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function get(int|string $id): JsonResponse
    {
        $action = new PromotionGetAction($id);
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
     * @param PromotionReadRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ReflectionException
     */
    public function read(PromotionReadRequest $request): JsonResponse
    {
        $action = new PromotionReadAction(
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
     * @param PromotionCreateRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws Throwable
     */
    public function create(PromotionCreateRequest $request): JsonResponse
    {
        try {
            $data = PromotionCreate::from([
                ...$request->toArray(),
                'date_start' => Carbon::createFromFormat(
                    'Y-m-d O',
                    $request->get('date_start')
                )->setTimezone(Config::get('app.timezone')),
                'date_end' => Carbon::createFromFormat(
                    'Y-m-d O',
                    $request->get('date_end')
                )->setTimezone(Config::get('app.timezone')),
            ]);
            $action = new PromotionCreateAction($data);
            $data = $action->run();

            Log::info(
                trans('promotion::http.controllers.admin.promotionController.create.log'),
                [
                    'module' => 'Promotion',
                    'login' => Auth::getUser()->login,
                    'type' => 'create',
                ],
            );

            $data = [
                'data' => $data,
                'success' => true,
            ];

            return response()->json($data);
        } catch (ValidateException|TemplateException $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage(),
            ])->setStatusCode(400);
        } catch (RecordExistException $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage(),
            ])->setStatusCode(404);
        }
    }

    /**
     * Обновление данных.
     *
     * @param int|string $id ID промоакции.
     * @param PromotionUpdateRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws Throwable
     */
    public function update(int|string $id, PromotionUpdateRequest $request): JsonResponse
    {
        try {
            $data = PromotionUpdate::from([
                ...$request->toArray(),
                'date_start' => Carbon::createFromFormat(
                    'Y-m-d O',
                    $request->get('date_start')
                )->setTimezone(Config::get('app.timezone')),
                'date_end' => Carbon::createFromFormat(
                    'Y-m-d O',
                    $request->get('date_end')
                )->setTimezone(Config::get('app.timezone')),
                'id' => $id,
            ]);
            $action = new PromotionUpdateAction($data);
            $data = $action->run();

            Log::info(
                trans('promotion::http.controllers.admin.promotionController.update.log'),
                [
                    'module' => 'Promotion',
                    'login' => Auth::getUser()->login,
                    'type' => 'update',
                ]
            );

            $data = [
                'data' => $data,
                'success' => true,
            ];

            return response()->json($data);
        } catch (ValidateException|RecordExistException|TemplateException $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage(),
            ])->setStatusCode(400);
        } catch (RecordNotExistException $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage(),
            ])->setStatusCode(404);
        }
    }

    /**
     * Обновление статуса.
     *
     * @param int|string $id ID пользователя.
     * @param PromotionUpdateStatusRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function updateStatus(int|string $id, PromotionUpdateStatusRequest $request): JsonResponse
    {
        try {
            $action = new PromotionUpdateStatusAction($id, $request->get('status'));
            $data = $action->run();

            Log::info(trans('promotion::http.controllers.admin.promotionController.update.log'), [
                'module' => 'User',
                'login' => Auth::getUser()->login,
                'type' => 'update',
            ]);

            $data = [
                'success' => true,
                'data' => $data,
            ];

            return response()->json($data);
        } catch (ValidateException|RecordExistException $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage(),
            ])->setStatusCode(400);
        } catch (RecordNotExistException $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage(),
            ])->setStatusCode(404);
        }
    }

    /**
     * Удаление данных.
     *
     * @param PromotionDestroyRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function destroy(PromotionDestroyRequest $request): JsonResponse
    {
        $action = new PromotionDestroyAction($request->get('ids'));
        $action->run();

        Log::info(
            trans('promotion::http.controllers.admin.promotionController.destroy.log'),
            [
                'module' => 'Promotion',
                'login' => Auth::getUser()->login,
                'type' => 'destroy',
            ]
        );

        $data = [
            'success' => true,
        ];

        return response()->json($data);
    }
}
