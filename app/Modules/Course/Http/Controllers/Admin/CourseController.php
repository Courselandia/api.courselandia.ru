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
use Throwable;
use ReflectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use App\Modules\Course\Enums\Currency;
use App\Modules\Course\Enums\Duration;
use App\Modules\Course\Enums\Language;
use App\Modules\Course\Enums\Status;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordExistException;
use App\Models\Exceptions\RecordNotExistException;
use App\Models\Exceptions\ValidateException;
use App\Modules\Course\Actions\Admin\Course\CourseCreateAction;
use App\Modules\Course\Actions\Admin\Course\CourseDestroyAction;
use App\Modules\Course\Actions\Admin\Course\CourseGetAction;
use App\Modules\Course\Actions\Admin\Course\CourseReadAction;
use App\Modules\Course\Actions\Admin\Course\CourseUpdateAction;
use App\Modules\Course\Http\Requests\Admin\Course\CourseCreateRequest;
use App\Modules\Course\Http\Requests\Admin\Course\CourseDestroyRequest;
use App\Modules\Course\Http\Requests\Admin\Course\CourseReadRequest;
use App\Modules\Course\Http\Requests\Admin\Course\CourseUpdateRequest;
use App\Modules\Course\Data\Actions\CourseCreate;
use App\Modules\Course\Data\Actions\CourseUpdate;
use App\Modules\Metatag\Template\TemplateException;

/**
 * Класс контроллер для работы с курсами в административной части.
 */
class CourseController extends Controller
{
    /**
     * Получение курса.
     *
     * @param int|string $id ID курса.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function get(int|string $id): JsonResponse
    {
        $action = new CourseGetAction($id);
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
     * @throws ParameterInvalidException
     */
    public function read(CourseReadRequest $request): JsonResponse
    {
        $action = new CourseReadAction(
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
     * @param CourseCreateRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws RecordNotExistException|ParameterInvalidException|ReflectionException|Throwable
     */
    public function create(CourseCreateRequest $request): JsonResponse
    {
        try {
            $data = CourseCreate::from([
                ...$request->all(),
                'language' => $request->get('language') ? Language::from($request->get('language')) : null,
                'currency' => $request->get('currency') ? Currency::from($request->get('currency')) : null,
                'duration_unit' => $request->get('duration_unit') ? Duration::from(
                    $request->get('duration_unit')
                ) : null,
                'program' => json_decode($request->get('program'), true),
                'status' => Status::from($request->get('status')),
            ]);

            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $data->image = $request->file('image');
            }

            $action = new CourseCreateAction($data);
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
     * @param int|string $id ID курса.
     * @param CourseUpdateRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException|ReflectionException|Throwable
     */
    public function update(int|string $id, CourseUpdateRequest $request): JsonResponse
    {
        try {
            $data = CourseUpdate::from([
                ...$request->toArray(),
                'id' => $id,
                'language' => $request->get('language') ? Language::from($request->get('language')) : null,
                'currency' => $request->get('currency') ? Currency::from($request->get('currency')) : null,
                'duration_unit' => $request->get('duration_unit') ? Duration::from(
                    $request->get('duration_unit')
                ) : null,
                'program' => json_decode($request->get('program'), true),
                'status' => Status::from($request->get('status')),
            ]);

            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $data->image = $request->file('image');
            }

            $action = new CourseUpdateAction($data);
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
     * Удаление данных.
     *
     * @param CourseDestroyRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function destroy(CourseDestroyRequest $request): JsonResponse
    {
        $action = new CourseDestroyAction($request->get('ids'));
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
