<?php
/**
 * Модуль Категорий.
 * Этот модуль содержит все классы для работы с категориями.
 *
 * @package App\Modules\Category
 */

namespace App\Modules\Category\Http\Controllers\Admin;

use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordExistException;
use App\Models\Exceptions\RecordNotExistException;
use App\Models\Exceptions\ValidateException;
use App\Modules\Category\Actions\Admin\CategoryCreateAction;
use App\Modules\Category\Actions\Admin\CategoryDestroyAction;
use App\Modules\Category\Actions\Admin\CategoryGetAction;
use App\Modules\Category\Actions\Admin\CategoryReadAction;
use App\Modules\Category\Actions\Admin\CategoryUpdateAction;
use App\Modules\Category\Actions\Admin\CategoryUpdateStatusAction;
use App\Modules\Category\Http\Requests\Admin\CategoryCreateRequest;
use App\Modules\Category\Http\Requests\Admin\CategoryDestroyRequest;
use App\Modules\Category\Http\Requests\Admin\CategoryReadRequest;
use App\Modules\Category\Http\Requests\Admin\CategoryUpdateRequest;
use App\Modules\Category\Http\Requests\Admin\CategoryUpdateStatusRequest;
use Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Log;
use ReflectionException;

/**
 * Класс контроллер для работы с категориями в административной части.
 */
class CategoryController extends Controller
{
    /**
     * Получение категории.
     *
     * @param int|string $id ID категории.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException|ReflectionException
     */
    public function get(int|string $id): JsonResponse
    {
        $action = app(CategoryGetAction::class);
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
     * @param CategoryReadRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException|ReflectionException
     */
    public function read(CategoryReadRequest $request): JsonResponse
    {
        $action = app(CategoryReadAction::class);
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
     * @param CategoryCreateRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws RecordNotExistException|ParameterInvalidException|ReflectionException
     */
    public function create(CategoryCreateRequest $request): JsonResponse
    {
        try {
            $action = app(CategoryCreateAction::class);
            $action->name = $request->get('name');
            $action->header = $request->get('header');
            $action->link = $request->get('link');
            $action->text = $request->get('text');
            $action->directions = $request->get('directions');
            $action->professions = $request->get('professions');
            $action->status = $request->get('status');
            $action->title = $request->get('title');
            $action->description = $request->get('description');
            $action->keywords = $request->get('keywords');

            $data = $action->run();

            Log::info(
                trans('category::http.controllers.admin.categoryController.create.log'),
                [
                    'module' => 'Category',
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
     * @param int|string $id ID категории.
     * @param CategoryUpdateRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException|ReflectionException
     */
    public function update(int|string $id, CategoryUpdateRequest $request): JsonResponse
    {
        try {
            $action = app(CategoryUpdateAction::class);
            $action->id = $id;
            $action->name = $request->get('name');
            $action->header = $request->get('header');
            $action->link = $request->get('link');
            $action->text = $request->get('text');
            $action->directions = $request->get('directions');
            $action->professions = $request->get('professions');
            $action->status = $request->get('status');
            $action->title = $request->get('title');
            $action->description = $request->get('description');
            $action->keywords = $request->get('keywords');
            $data = $action->run();

            Log::info(
                trans('category::http.controllers.admin.categoryController.update.log'),
                [
                    'module' => 'Category',
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
     * @param CategoryUpdateStatusRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException|ReflectionException
     */
    public function updateStatus(int|string $id, CategoryUpdateStatusRequest $request): JsonResponse
    {
        try {
            $action = app(CategoryUpdateStatusAction::class);
            $action->id = $id;
            $action->status = $request->get('status');

            $data = $action->run();

            Log::info(trans('category::http.controllers.admin.categoryController.update.log'), [
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
        } catch (RecordNotExistException|RecordNotExistException $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage()
            ])->setStatusCode(404);
        }
    }

    /**
     * Удаление данных.
     *
     * @param CategoryDestroyRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function destroy(CategoryDestroyRequest $request): JsonResponse
    {
        $action = app(CategoryDestroyAction::class);
        $action->ids = $request->get('ids');
        $action->run();

        Log::info(
            trans('category::http.controllers.admin.categoryController.destroy.log'),
            [
                'module' => 'Category',
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
