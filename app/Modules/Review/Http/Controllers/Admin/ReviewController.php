<?php
/**
 * Модуль Отзывов.
 * Этот модуль содержит все классы для работы с отзывами.
 *
 * @package App\Modules\Review
 */

namespace App\Modules\Review\Http\Controllers\Admin;

use App\Modules\Review\Data\Admin\ReviewCreate;
use App\Modules\Review\Data\Admin\ReviewUpdate;
use Config;
use App\Modules\Review\Enums\Status;
use App\Modules\Review\Http\Requests\Admin\ReviewCreateRequest;
use App\Modules\Review\Http\Requests\Admin\ReviewUpdateRequest;
use Auth;
use Carbon\Carbon;
use Log;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use App\Models\Exceptions\RecordExistException;
use App\Models\Exceptions\RecordNotExistException;
use App\Models\Exceptions\ValidateException;
use App\Modules\Review\Actions\Admin\ReviewCreateAction;
use App\Modules\Review\Actions\Admin\ReviewDestroyAction;
use App\Modules\Review\Actions\Admin\ReviewGetAction;
use App\Modules\Review\Actions\Admin\ReviewReadAction;
use App\Modules\Review\Actions\Admin\ReviewUpdateAction;
use App\Modules\Review\Http\Requests\Admin\ReviewDestroyRequest;
use App\Modules\Review\Http\Requests\Admin\ReviewReadRequest;

/**
 * Класс контроллер для работы с отзывами в административной части.
 */
class ReviewController extends Controller
{
    /**
     * Получение отзывов.
     *
     * @param int|string $id ID отзывов.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function get(int|string $id): JsonResponse
    {
        $action = new ReviewGetAction($id);
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
     */
    public function read(ReviewReadRequest $request): JsonResponse
    {
        $action = new ReviewReadAction(
            $request->get('sorts'),
            $request->get('filters'),
            $request->get('offset'),
            $request->get('limit')
        );

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
     */
    public function create(ReviewCreateRequest $request): JsonResponse
    {
        try {
            $data = ReviewCreate::from([
                ...$request->all(),
                'status' => Status::from($request->get('status')),
                'created_at' => Carbon::createFromFormat(
                    'Y-m-d H:i:s O',
                    $request->get('created_at')
                )->setTimezone(Config::get('app.timezone'))
            ]);

            $action = new ReviewCreateAction($data);
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
     * @param ReviewUpdateRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function update(int|string $id, ReviewUpdateRequest $request): JsonResponse
    {
        try {
            $data = ReviewUpdate::from([
                'id' => $id,
                ...$request->all(),
                'status' => Status::from($request->get('status')),
                'created_at' => Carbon::createFromFormat(
                    'Y-m-d H:i:s O',
                    $request->get('created_at')
                )->setTimezone(Config::get('app.timezone'))
            ]);

            $action = new ReviewUpdateAction($data);
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
     * Удаление данных.
     *
     * @param ReviewDestroyRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function destroy(ReviewDestroyRequest $request): JsonResponse
    {
        $action = new ReviewDestroyAction($request->get('ids'));
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
