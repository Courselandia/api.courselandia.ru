<?php
/**
 * Модуль Категорий.
 * Этот модуль содержит все классы для работы с категориями.
 *
 * @package App\Modules\Category
 */

namespace App\Modules\Category\Http\Controllers\Admin;

use App\Models\Exceptions\RecordExistException;
use App\Models\Exceptions\RecordNotExistException;
use App\Models\Exceptions\ValidateException;
use App\Modules\Category\Actions\Admin\CategoryCreateAction;
use App\Modules\Category\Actions\Admin\CategoryDestroyAction;
use App\Modules\Category\Actions\Admin\CategoryGetAction;
use App\Modules\Category\Actions\Admin\CategoryReadAction;
use App\Modules\Category\Actions\Admin\CategoryUpdateAction;
use App\Modules\Category\Actions\Admin\CategoryUpdateStatusAction;
use App\Modules\Category\Data\CategoryCreate;
use App\Modules\Category\Data\CategoryUpdate;
use App\Modules\Category\Http\Requests\Admin\CategoryCreateRequest;
use App\Modules\Category\Http\Requests\Admin\CategoryDestroyRequest;
use App\Modules\Category\Http\Requests\Admin\CategoryReadRequest;
use App\Modules\Category\Http\Requests\Admin\CategoryUpdateRequest;
use App\Modules\Category\Http\Requests\Admin\CategoryUpdateStatusRequest;
use App\Modules\Metatag\Template\TemplateException;
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
     */
    public function get(int|string $id): JsonResponse
    {
        $action = new CategoryGetAction($id);
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
     * @throws ReflectionException
     */
    public function read(CategoryReadRequest $request): JsonResponse
    {
        $action = new CategoryReadAction(
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
     * @param CategoryCreateRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function create(CategoryCreateRequest $request): JsonResponse
    {
        try {
            $data = CategoryCreate::from($request->all());
            $action = new CategoryCreateAction($data);
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
     * @param int|string $id ID категории.
     * @param CategoryUpdateRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function update(int|string $id, CategoryUpdateRequest $request): JsonResponse
    {
        try {
            $data = CategoryUpdate::from([
                ...$request->all(),
                'id' => $id,
            ]);
            $action = new CategoryUpdateAction($data);
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
     * @param CategoryUpdateStatusRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function updateStatus(int|string $id, CategoryUpdateStatusRequest $request): JsonResponse
    {
        try {
            $action = new CategoryUpdateStatusAction($id, $request->get('status'));
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
        $action = new CategoryDestroyAction($request->get('ids'));
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
