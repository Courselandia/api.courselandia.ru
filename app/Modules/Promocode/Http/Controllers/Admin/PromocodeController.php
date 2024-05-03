<?php
/**
 * Модуль Промокодов.
 * Этот модуль содержит все классы для работы с промокодами.
 *
 * @package App\Modules\Promocode
 */

namespace App\Modules\Promocode\Http\Controllers\Admin;

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
use App\Modules\Promocode\Actions\Admin\PromocodeCreateAction;
use App\Modules\Promocode\Actions\Admin\PromocodeDestroyAction;
use App\Modules\Promocode\Actions\Admin\PromocodeGetAction;
use App\Modules\Promocode\Actions\Admin\PromocodeReadAction;
use App\Modules\Promocode\Actions\Admin\PromocodeUpdateAction;
use App\Modules\Promocode\Actions\Admin\PromocodeUpdateStatusAction;
use App\Modules\Promocode\Data\PromocodeCreate;
use App\Modules\Promocode\Data\PromocodeUpdate;
use App\Modules\Promocode\Http\Requests\Admin\PromocodeCreateRequest;
use App\Modules\Promocode\Http\Requests\Admin\PromocodeDestroyRequest;
use App\Modules\Promocode\Http\Requests\Admin\PromocodeReadRequest;
use App\Modules\Promocode\Http\Requests\Admin\PromocodeUpdateRequest;
use App\Modules\Promocode\Http\Requests\Admin\PromocodeUpdateStatusRequest;
use App\Modules\Promocode\Enums\DiscountType;
use App\Modules\Promocode\Enums\Type;

/**
 * Класс контроллер для работы с промокодов в административной части.
 */
class PromocodeController extends Controller
{
    /**
     * Получение промокодов.
     *
     * @param int|string $id ID промокода.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws Throwable
     */
    public function get(int|string $id): JsonResponse
    {
        $action = new PromocodeGetAction($id);
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
     * @param PromocodeReadRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ReflectionException|Throwable
     */
    public function read(PromocodeReadRequest $request): JsonResponse
    {
        $action = new PromocodeReadAction(
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
     * @param PromocodeCreateRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws Throwable
     */
    public function create(PromocodeCreateRequest $request): JsonResponse
    {
        try {
            $data = PromocodeCreate::from([
                ...$request->toArray(),
                'date_start' => Carbon::createFromFormat(
                    'Y-m-d O',
                    $request->get('date_start')
                )->setTimezone(Config::get('app.timezone')),
                'date_end' => Carbon::createFromFormat(
                    'Y-m-d O',
                    $request->get('date_end')
                )->setTimezone(Config::get('app.timezone')),
                'discount_type' => DiscountType::from($request->get('discount_type')),
                'type' => Type::from($request->get('type')),
            ]);
            $action = new PromocodeCreateAction($data);
            $data = $action->run();

            Log::info(
                trans('promocode::http.controllers.admin.promocodeController.create.log'),
                [
                    'module' => 'Promocode',
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
     * @param int|string $id ID промокода.
     * @param PromocodeUpdateRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws Throwable
     */
    public function update(int|string $id, PromocodeUpdateRequest $request): JsonResponse
    {
        try {
            $data = PromocodeUpdate::from([
                ...$request->toArray(),
                'date_start' => Carbon::createFromFormat(
                    'Y-m-d O',
                    $request->get('date_start')
                )->setTimezone(Config::get('app.timezone')),
                'date_end' => Carbon::createFromFormat(
                    'Y-m-d O',
                    $request->get('date_end')
                )->setTimezone(Config::get('app.timezone')),
                'discount_type' => DiscountType::from($request->get('discount_type')),
                'type' => Type::from($request->get('type')),
                'id' => $id,
            ]);
            $action = new PromocodeUpdateAction($data);
            $data = $action->run();

            Log::info(
                trans('promocode::http.controllers.admin.promocodeController.update.log'),
                [
                    'module' => 'Promocode',
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
     * @param PromocodeUpdateStatusRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws Throwable
     */
    public function updateStatus(int|string $id, PromocodeUpdateStatusRequest $request): JsonResponse
    {
        try {
            $action = new PromocodeUpdateStatusAction($id, $request->get('status'));
            $data = $action->run();

            Log::info(trans('promocode::http.controllers.admin.promocodeController.update.log'), [
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
     * @param PromocodeDestroyRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function destroy(PromocodeDestroyRequest $request): JsonResponse
    {
        $action = new PromocodeDestroyAction($request->get('ids'));
        $action->run();

        Log::info(
            trans('promocode::http.controllers.admin.promocodeController.destroy.log'),
            [
                'module' => 'Promocode',
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
