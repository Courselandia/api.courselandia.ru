<?php
/**
 * Модуль Отзывов.
 * Этот модуль содержит все классы для работы с отзывовами.
 *
 * @package App\Modules\Review
 */

namespace App\Modules\Review\Http\Controllers\Admin;

use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordExistException;
use App\Models\Exceptions\RecordNotExistException;
use App\Models\Exceptions\UserNotExistException;
use App\Models\Exceptions\ValidateException;
use App\Modules\Review\Actions\Admin\ReviewCreateAction;
use App\Modules\Review\Actions\Admin\ReviewDestroyAction;
use App\Modules\Review\Actions\Admin\ReviewGetAction;
use App\Modules\Review\Actions\Admin\ReviewReadAction;
use App\Modules\Review\Actions\Admin\ReviewUpdateAction;
use App\Modules\Review\Actions\Admin\ReviewUpdateStatusAction;
use App\Modules\Review\Enums\Level;
use App\Modules\Review\Http\Requests\Admin\ReviewCreateRequest;
use App\Modules\Review\Http\Requests\Admin\ReviewDestroyRequest;
use App\Modules\Review\Http\Requests\Admin\ReviewReadRequest;
use App\Modules\Review\Http\Requests\Admin\ReviewUpdateStatusRequest;
use Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Log;
use ReflectionException;

/**
 * Класс контроллер для работы с отзывовами в административной части.
 */
class ReviewController extends Controller
{
    /**
     * Получение отзывов.
     *
     * @param int|string $id ID отзывов.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException|ReflectionException
     */
    public function get(int|string $id): JsonResponse
    {
        $action = app(ReviewGetAction::class);
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
     * @param ReviewReadRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException|ReflectionException
     */
    public function read(ReviewReadRequest $request): JsonResponse
    {
        $action = app(ReviewReadAction::class);
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
     * @param ReviewCreateRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException|ReflectionException
     */
    public function create(ReviewCreateRequest $request): JsonResponse
    {
        try {
            $action = app(ReviewCreateAction::class);
            $action->profession_id = $request->get('profession_id');
            $action->level = Level::from($request->get('level'));
            $action->review = $request->get('review');
            $action->status = $request->get('status');

            $data = $action->run();

            Log::info(
                trans('review::http.controllers.admin.reviewController.create.log'),
                [
                    'module' => 'Review',
                    'login' => Auth::getUser()->login,
                    'type' => 'create'
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
        }
    }

    /**
     * Обновление данных.
     *
     * @param int|string $id ID отзывов.
     * @param ReviewCreateRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException
     */
    public function update(int|string $id, ReviewCreateRequest $request): JsonResponse
    {
        try {
            $action = app(ReviewUpdateAction::class);
            $action->id = $id;
            $action->profession_id = $request->get('profession_id');
            $action->level = Level::from($request->get('level'));
            $action->review = $request->get('review');
            $action->status = $request->get('status');
            $data = $action->run();

            Log::info(
                trans('review::http.controllers.admin.reviewController.update.log'),
                [
                    'module' => 'Review',
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
     * @param ReviewUpdateStatusRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException|ReflectionException
     */
    public function updateStatus(int|string $id, ReviewUpdateStatusRequest $request): JsonResponse
    {
        try {
            $action = app(ReviewUpdateStatusAction::class);
            $action->id = $id;
            $action->status = $request->get('status');

            $data = $action->run();

            Log::info(trans('review::http.controllers.admin.reviewController.update.log'), [
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
     * @param ReviewDestroyRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException
     */
    public function destroy(ReviewDestroyRequest $request): JsonResponse
    {
        $action = app(ReviewDestroyAction::class);
        $action->ids = $request->get('ids');
        $action->run();

        Log::info(
            trans('review::http.controllers.admin.reviewController.destroy.log'),
            [
                'module' => 'Review',
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
