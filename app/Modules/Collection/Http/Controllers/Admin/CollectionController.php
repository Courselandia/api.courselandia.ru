<?php
/**
 * Модуль Коллекций.
 * Этот модуль содержит все классы для работы с коллекциями.
 *
 * @package App\Modules\Collection
 */

namespace App\Modules\Collection\Http\Controllers\Admin;

use App\Modules\Collection\Actions\Admin\Collection\CollectionCoursesByFiltersAction;
use App\Modules\Collection\Data\CollectionCoursesByFilters;
use App\Modules\Collection\Http\Requests\Admin\Collection\CollectionCountRequest;
use Auth;
use Log;
use Throwable;
use ReflectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordExistException;
use App\Models\Exceptions\RecordNotExistException;
use App\Models\Exceptions\ValidateException;
use App\Modules\Metatag\Template\TemplateException;
use App\Modules\Collection\Data\CollectionCreate;
use App\Modules\Collection\Data\CollectionUpdate;
use App\Modules\Collection\Data\CollectionFilter;
use App\Modules\Collection\Actions\Admin\Collection\CollectionCreateAction;
use App\Modules\Collection\Actions\Admin\Collection\CollectionDestroyAction;
use App\Modules\Collection\Actions\Admin\Collection\CollectionGetAction;
use App\Modules\Collection\Actions\Admin\Collection\CollectionReadAction;
use App\Modules\Collection\Actions\Admin\Collection\CollectionUpdateAction;
use App\Modules\Collection\Actions\Admin\Collection\CollectionUpdateStatusAction;
use App\Modules\Collection\Http\Requests\Admin\Collection\CollectionCreateRequest;
use App\Modules\Collection\Http\Requests\Admin\Collection\CollectionDestroyRequest;
use App\Modules\Collection\Http\Requests\Admin\Collection\CollectionReadRequest;
use App\Modules\Collection\Http\Requests\Admin\Collection\CollectionUpdateRequest;
use App\Modules\Collection\Http\Requests\Admin\Collection\CollectionUpdateStatusRequest;

/**
 * Класс контроллер для работы с коллекциям в административной части.
 */
class CollectionController extends Controller
{
    /**
     * Получение коллекции.
     *
     * @param int|string $id ID коллекции.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function get(int|string $id): JsonResponse
    {
        $action = new CollectionGetAction($id);
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
     * @param CollectionReadRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException|ReflectionException
     */
    public function read(CollectionReadRequest $request): JsonResponse
    {
        $action = new CollectionReadAction(
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
     * @param CollectionCreateRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException|Throwable
     */
    public function create(CollectionCreateRequest $request): JsonResponse
    {
        try {
            $data = CollectionCreate::from([
                ...$request->all(),
                'filters' => CollectionFilter::collection(collect($request->get('filters'))
                    ->map(static function ($filter) {
                        return CollectionFilter::from($filter);
                    })
                    ->toArray()),
            ]);

            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $data->image = $request->file('image');
            }

            $action = new CollectionCreateAction($data);
            $data = $action->run();

            Log::info(
                trans('collection::http.controllers.admin.collectionController.create.log'),
                [
                    'module' => 'Collection',
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
     * @param int|string $id ID коллекции.
     * @param CollectionUpdateRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException|Throwable
     */
    public function update(int|string $id, CollectionUpdateRequest $request): JsonResponse
    {
        try {
            $dataAll = $request->all();

            $data = CollectionUpdate::from([
                ...$dataAll,
                'id' => $id,
                'filters' => CollectionFilter::collection(collect($dataAll['filters'])
                    ->map(static function ($filter) {
                        return CollectionFilter::from($filter);
                    })
                    ->toArray()),
            ]);

            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $data->image = $request->file('image');
            }

            $action = new CollectionUpdateAction($data);
            $data = $action->run();

            Log::info(
                trans('collection::http.controllers.admin.collectionController.update.log'),
                [
                    'module' => 'Collection',
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
     * @param CollectionUpdateStatusRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function updateStatus(int|string $id, CollectionUpdateStatusRequest $request): JsonResponse
    {
        try {
            $action = new CollectionUpdateStatusAction($id, $request->get('status'));
            $data = $action->run();

            Log::info(trans('collection::http.controllers.admin.collectionController.update.log'), [
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
     * @param CollectionDestroyRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function destroy(CollectionDestroyRequest $request): JsonResponse
    {
        $action = new CollectionDestroyAction($request->get('ids'));
        $action->run();

        Log::info(
            trans('collection::http.controllers.admin.collectionController.destroy.log'),
            [
                'module' => 'Collection',
                'login' => Auth::getUser()->login,
                'type' => 'destroy'
            ]
        );

        $data = [
            'success' => true
        ];

        return response()->json($data);
    }

    /**
     * Получения количество курсов в коллекции.
     *
     * @param CollectionCountRequest $request Запрос.
     *
     * @return JsonResponse
     */
    public function count(CollectionCountRequest $request): JsonResponse
    {
        $data = CollectionCoursesByFilters::from([
            'filters' => CollectionFilter::collection(collect($request->get('filters'))
                ->map(static function ($filter) {
                    return CollectionFilter::from($filter);
                })
                ->toArray()),
            'onlyCount' => true,
        ]);

        $action = new CollectionCoursesByFiltersAction($data);
        $count = $action->run();

        $data = [
            'data' => [
                'count' => $count,
            ],
            'success' => true,
        ];

        return response()->json($data);
    }
}
