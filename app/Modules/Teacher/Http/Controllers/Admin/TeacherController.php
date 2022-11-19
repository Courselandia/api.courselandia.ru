<?php
/**
 * Модуль Учителей.
 * Этот модуль содержит все классы для работы с учителями.
 *
 * @package App\Modules\Teacher
 */

namespace App\Modules\Teacher\Http\Controllers\Admin;

use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordExistException;
use App\Models\Exceptions\RecordNotExistException;
use App\Models\Exceptions\ValidateException;
use App\Modules\Teacher\Actions\Admin\Teacher\TeacherCreateAction;
use App\Modules\Teacher\Actions\Admin\Teacher\TeacherDestroyAction;
use App\Modules\Teacher\Actions\Admin\Teacher\TeacherGetAction;
use App\Modules\Teacher\Actions\Admin\Teacher\TeacherReadAction;
use App\Modules\Teacher\Actions\Admin\Teacher\TeacherUpdateAction;
use App\Modules\Teacher\Actions\Admin\Teacher\TeacherUpdateStatusAction;
use App\Modules\Teacher\Http\Requests\Admin\Teacher\TeacherCreateRequest;
use App\Modules\Teacher\Http\Requests\Admin\Teacher\TeacherDestroyRequest;
use App\Modules\Teacher\Http\Requests\Admin\Teacher\TeacherReadRequest;
use App\Modules\Teacher\Http\Requests\Admin\Teacher\TeacherUpdateRequest;
use App\Modules\Teacher\Http\Requests\Admin\Teacher\TeacherUpdateStatusRequest;
use Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Log;
use ReflectionException;

/**
 * Класс контроллер для работы с учителями в административной части.
 */
class TeacherController extends Controller
{
    /**
     * Получение учителя.
     *
     * @param int|string $id ID учителя.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException
     */
    public function get(int|string $id): JsonResponse
    {
        $action = app(TeacherGetAction::class);
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
     * @param TeacherReadRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException|ReflectionException
     */
    public function read(TeacherReadRequest $request): JsonResponse
    {
        $action = app(TeacherReadAction::class);
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
     * @param TeacherCreateRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException
     */
    public function create(TeacherCreateRequest $request): JsonResponse
    {
        try {
            $action = app(TeacherCreateAction::class);
            $action->name = $request->get('name');
            $action->link = $request->get('link');
            $action->text = $request->get('text');
            $action->rating = $request->get('rating');
            $action->status = $request->get('status');
            $action->title = $request->get('title');
            $action->description = $request->get('description');
            $action->keywords = $request->get('keywords');
            $action->directions = $request->get('directions');
            $action->schools = $request->get('schools');

            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $action->image = $request->file('image');
            }

            $data = $action->run();

            Log::info(
                trans('teacher::http.controllers.admin.teacherController.create.log'),
                [
                    'module' => 'Teacher',
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
     * @param int|string $id ID учителя.
     * @param TeacherUpdateRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException
     */
    public function update(int|string $id, TeacherUpdateRequest $request): JsonResponse
    {
        try {
            $action = app(TeacherUpdateAction::class);
            $action->id = $id;
            $action->name = $request->get('name');
            $action->link = $request->get('link');
            $action->text = $request->get('text');
            $action->rating = $request->get('rating');
            $action->status = $request->get('status');
            $action->title = $request->get('title');
            $action->description = $request->get('description');
            $action->keywords = $request->get('keywords');
            $action->directions = $request->get('directions');
            $action->schools = $request->get('schools');

            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $action->image = $request->file('image');
            }

            $data = $action->run();

            Log::info(
                trans('teacher::http.controllers.admin.teacherController.update.log'),
                [
                    'module' => 'Teacher',
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
     * @param TeacherUpdateStatusRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException
     */
    public function updateStatus(int|string $id, TeacherUpdateStatusRequest $request): JsonResponse
    {
        try {
            $action = app(TeacherUpdateStatusAction::class);
            $action->id = $id;
            $action->status = $request->get('status');

            $data = $action->run();

            Log::info(trans('teacher::http.controllers.admin.teacherController.update.log'), [
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
     * @param TeacherDestroyRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function destroy(TeacherDestroyRequest $request): JsonResponse
    {
        $action = app(TeacherDestroyAction::class);
        $action->ids = $request->get('ids');
        $action->run();

        Log::info(
            trans('teacher::http.controllers.admin.teacherController.destroy.log'),
            [
                'module' => 'Teacher',
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
