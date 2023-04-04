<?php
/**
 * Модуль Инструментов.
 * Этот модуль содержит все классы для работы с инструментами.
 *
 * @package App\Modules\Tool
 */

namespace App\Modules\Tool\Http\Controllers\Admin;

use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordExistException;
use App\Models\Exceptions\RecordNotExistException;
use App\Models\Exceptions\ValidateException;
use App\Modules\Metatag\Template\TemplateException;
use App\Modules\Tool\Actions\Admin\ToolCreateAction;
use App\Modules\Tool\Actions\Admin\ToolDestroyAction;
use App\Modules\Tool\Actions\Admin\ToolGetAction;
use App\Modules\Tool\Actions\Admin\ToolReadAction;
use App\Modules\Tool\Actions\Admin\ToolUpdateAction;
use App\Modules\Tool\Actions\Admin\ToolUpdateStatusAction;
use App\Modules\Tool\Http\Requests\Admin\ToolCreateRequest;
use App\Modules\Tool\Http\Requests\Admin\ToolDestroyRequest;
use App\Modules\Tool\Http\Requests\Admin\ToolReadRequest;
use App\Modules\Tool\Http\Requests\Admin\ToolUpdateRequest;
use App\Modules\Tool\Http\Requests\Admin\ToolUpdateStatusRequest;
use Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Log;
use ReflectionException;

/**
 * Класс контроллер для работы с инструментами в административной части.
 */
class ToolController extends Controller
{
    /**
     * Получение инструмента.
     *
     * @param int|string $id ID инструмента.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException
     */
    public function get(int|string $id): JsonResponse
    {
        $action = app(ToolGetAction::class);
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
     * @param ToolReadRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException|ReflectionException
     */
    public function read(ToolReadRequest $request): JsonResponse
    {
        $action = app(ToolReadAction::class);
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
     * @param ToolCreateRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException
     */
    public function create(ToolCreateRequest $request): JsonResponse
    {
        try {
            $action = app(ToolCreateAction::class);
            $action->name = $request->get('name');
            $action->header_template = $request->get('header_template');
            $action->link = $request->get('link');
            $action->text = $request->get('text');
            $action->status = $request->get('status');
            $action->description_template = $request->get('description_template');
            $action->title_template = $request->get('title_template');
            $action->keywords = $request->get('keywords');

            $data = $action->run();

            Log::info(
                trans('tool::http.controllers.admin.toolController.create.log'),
                [
                    'module' => 'Tool',
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
     * @param int|string $id ID инструмента.
     * @param ToolUpdateRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException
     */
    public function update(int|string $id, ToolUpdateRequest $request): JsonResponse
    {
        try {
            $action = app(ToolUpdateAction::class);
            $action->id = $id;
            $action->name = $request->get('name');
            $action->header_template = $request->get('header_template');
            $action->link = $request->get('link');
            $action->text = $request->get('text');
            $action->status = $request->get('status');
            $action->description_template = $request->get('description_template');
            $action->title_template = $request->get('title_template');
            $action->keywords = $request->get('keywords');
            $data = $action->run();

            Log::info(
                trans('tool::http.controllers.admin.toolController.update.log'),
                [
                    'module' => 'Tool',
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
     * @param ToolUpdateStatusRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException
     */
    public function updateStatus(int|string $id, ToolUpdateStatusRequest $request): JsonResponse
    {
        try {
            $action = app(ToolUpdateStatusAction::class);
            $action->id = $id;
            $action->status = $request->get('status');

            $data = $action->run();

            Log::info(trans('tool::http.controllers.admin.toolController.update.log'), [
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
     * @param ToolDestroyRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function destroy(ToolDestroyRequest $request): JsonResponse
    {
        $action = app(ToolDestroyAction::class);
        $action->ids = $request->get('ids');
        $action->run();

        Log::info(
            trans('tool::http.controllers.admin.toolController.destroy.log'),
            [
                'module' => 'Tool',
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
