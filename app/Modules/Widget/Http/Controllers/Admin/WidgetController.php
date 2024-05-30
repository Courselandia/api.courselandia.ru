<?php
/**
 * Модуль Виджетов.
 * Этот модуль содержит все классы для работы с виджетами, которые можно использовать в публикациях.
 *
 * @package App\Modules\Widget
 */

namespace App\Modules\Widget\Http\Controllers\Admin;

use Log;
use Auth;
use Throwable;
use ReflectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use App\Models\Exceptions\RecordExistException;
use App\Models\Exceptions\RecordNotExistException;
use App\Models\Exceptions\ValidateException;
use App\Modules\Metatag\Template\TemplateException;
use App\Modules\Widget\Actions\Admin\WidgetCreateAction;
use App\Modules\Widget\Actions\Admin\WidgetDestroyAction;
use App\Modules\Widget\Actions\Admin\WidgetGetAction;
use App\Modules\Widget\Actions\Admin\WidgetReadAction;
use App\Modules\Widget\Actions\Admin\WidgetUpdateAction;
use App\Modules\Widget\Actions\Admin\WidgetUpdateStatusAction;
use App\Modules\Widget\Data\WidgetCreate;
use App\Modules\Widget\Data\WidgetUpdate;
use App\Modules\Widget\Data\WidgetValue;
use App\Modules\Widget\Http\Requests\Admin\WidgetCreateRequest;
use App\Modules\Widget\Http\Requests\Admin\WidgetDestroyRequest;
use App\Modules\Widget\Http\Requests\Admin\WidgetReadRequest;
use App\Modules\Widget\Http\Requests\Admin\WidgetUpdateRequest;
use App\Modules\Widget\Http\Requests\Admin\WidgetUpdateStatusRequest;

/**
 * Класс контроллер для работы с виджетами в административной части.
 */
class WidgetController extends Controller
{
    /**
     * Получение виджета.
     *
     * @param int|string $id ID виджета.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function get(int|string $id): JsonResponse
    {
        $action = new WidgetGetAction($id);
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
     * @param WidgetReadRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ReflectionException
     */
    public function read(WidgetReadRequest $request): JsonResponse
    {
        $action = new WidgetReadAction(
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
     * @param WidgetCreateRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws Throwable
     */
    public function create(WidgetCreateRequest $request): JsonResponse
    {
        try {
            $data = WidgetCreate::from([
                ...$request->all(),
                'values' => WidgetValue::collect(collect($request->get('values'))
                    ->map(static function ($filter) {
                        return WidgetValue::from($filter);
                    })
                    ->toArray()),
            ]);

            $action = new WidgetCreateAction($data);
            $data = $action->run();

            Log::info(
                trans('widget::http.controllers.admin.widgetController.create.log'),
                [
                    'module' => 'Widget',
                    'login' => Auth::getUser()->login,
                    'type' => 'create'
                ]
            );

            $data = [
                'data' => $data,
                'success' => true
            ];

            return response()->json($data);
        } catch (ValidateException|TemplateException $error) {
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
     * @param int|string $id ID виджета.
     * @param WidgetUpdateRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws Throwable
     */
    public function update(int|string $id, WidgetUpdateRequest $request): JsonResponse
    {
        try {
            $dataAll = $request->all();

            $data = WidgetUpdate::from([
                ...$dataAll,
                'id' => $id,
                'values' => WidgetValue::collect(collect($dataAll['values'])
                    ->map(static function ($filter) {
                        return WidgetValue::from($filter);
                    })
                    ->toArray()),
            ]);


            $action = new WidgetUpdateAction($data);
            $data = $action->run();

            Log::info(
                trans('widget::http.controllers.admin.widgetController.update.log'),
                [
                    'module' => 'Widget',
                    'login' => Auth::getUser()->login,
                    'type' => 'update'
                ]
            );

            $data = [
                'data' => $data,
                'success' => true
            ];

            return response()->json($data);
        } catch (ValidateException|RecordExistException|TemplateException $error) {
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
     * @param WidgetUpdateStatusRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function updateStatus(int|string $id, WidgetUpdateStatusRequest $request): JsonResponse
    {
        try {
            $action = new WidgetUpdateStatusAction($id, $request->get('status'));
            $data = $action->run();

            Log::info(trans('widget::http.controllers.admin.widgetController.update.log'), [
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
        } catch (RecordNotExistException $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage()
            ])->setStatusCode(404);
        }
    }

    /**
     * Удаление данных.
     *
     * @param WidgetDestroyRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function destroy(WidgetDestroyRequest $request): JsonResponse
    {
        $action = new WidgetDestroyAction($request->get('ids'));
        $action->run();

        Log::info(
            trans('widget::http.controllers.admin.widgetController.destroy.log'),
            [
                'module' => 'Widget',
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
