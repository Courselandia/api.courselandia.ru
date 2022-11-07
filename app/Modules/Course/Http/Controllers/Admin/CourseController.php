<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Http\Controllers\Admin;

use Auth;
use Log;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use ReflectionException;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordExistException;
use App\Models\Exceptions\RecordNotExistException;
use App\Models\Exceptions\ValidateException;
use App\Modules\Course\Actions\Admin\CourseCreateAction;
use App\Modules\Course\Actions\Admin\CourseDestroyAction;
use App\Modules\Course\Actions\Admin\CourseGetAction;
use App\Modules\Course\Actions\Admin\CourseReadAction;
use App\Modules\Course\Actions\Admin\CourseUpdateAction;
use App\Modules\Course\Actions\Admin\CourseUpdateStatusAction;
use App\Modules\Course\Http\Requests\Admin\Course\CourseCreateRequest;
use App\Modules\Course\Http\Requests\Admin\Course\CourseDestroyRequest;
use App\Modules\Course\Http\Requests\Admin\Course\CourseReadRequest;
use App\Modules\Course\Http\Requests\Admin\Course\CourseUpdateRequest;

/**
 * Класс контроллер для работы с курсами в административной части.
 */
class CourseController extends Controller
{
    /**
     * Получение курса.
     *
     * @param int|string $id ID rehcf.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException|ReflectionException
     */
    public function get(int|string $id): JsonResponse
    {
        $action = app(CourseGetAction::class);
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
     * @param CourseReadRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException|ReflectionException
     */
    public function read(CourseReadRequest $request): JsonResponse
    {
        $action = app(CourseReadAction::class);
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
     * @param CourseCreateRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws RecordNotExistException|ParameterInvalidException|ReflectionException
     */
    public function create(CourseCreateRequest $request): JsonResponse
    {
        try {
            $action = app(CourseCreateAction::class);
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
                trans('course::http.controllers.admin.courseController.create.log'),
                [
                    'module' => 'Course',
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
     * @param int|string $id ID курса.
     * @param CourseUpdateRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException|ReflectionException
     */
    public function update(int|string $id, CourseUpdateRequest $request): JsonResponse
    {
        try {
            $action = app(CourseUpdateAction::class);
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
                trans('course::http.controllers.admin.courseController.update.log'),
                [
                    'module' => 'Course',
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
     * @param CourseDestroyRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function destroy(CourseDestroyRequest $request): JsonResponse
    {
        $action = app(CourseDestroyAction::class);
        $action->ids = $request->get('ids');
        $action->run();

        Log::info(
            trans('course::http.controllers.admin.courseController.destroy.log'),
            [
                'module' => 'Course',
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
