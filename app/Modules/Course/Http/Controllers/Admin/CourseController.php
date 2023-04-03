<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Http\Controllers\Admin;

use App\Modules\Metatag\Template\TemplateException;
use Auth;
use Log;
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
use Throwable;

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
     * @throws ParameterInvalidException
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
     * @throws ParameterInvalidException
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
     * @throws RecordNotExistException|ParameterInvalidException|ReflectionException|Throwable
     */
    public function create(CourseCreateRequest $request): JsonResponse
    {
        try {
            $action = app(CourseCreateAction::class);
            $action->school_id = $request->get('school_id');
            $action->name = $request->get('name');
            $action->header_template = $request->get('header_template');
            $action->text = $request->get('text');
            $action->link = $request->get('link');
            $action->url = $request->get('url');
            $action->language = $request->get('language') ? Language::from($request->get('language')) : null;
            $action->rating = $request->get('rating');
            $action->price = $request->get('price');
            $action->price_old = $request->get('price_old');
            $action->price_recurrent = $request->get('price_recurrent');
            $action->currency = $request->get('currency') ? Currency::from($request->get('currency')) : null;
            $action->online = $request->get('online');
            $action->employment = $request->get('employment');
            $action->duration = $request->get('duration');
            $action->duration_unit = $request->get('duration_unit') ? Duration::from(
                $request->get('duration_unit')
            ) : null;
            $action->lessons_amount = $request->get('lessons_amount');
            $action->modules_amount = $request->get('modules_amount');
            $action->status = Status::from($request->get('status'));

            $action->directions = $request->get('directions');
            $action->professions = $request->get('professions');
            $action->categories = $request->get('categories');
            $action->skills = $request->get('skills');
            $action->teachers = $request->get('teachers');
            $action->tools = $request->get('tools');
            $action->processes = $request->get('processes');
            $action->levels = $request->get('levels');
            $action->learns = $request->get('learns');
            $action->employments = $request->get('employments');
            $action->features = $request->get('features');

            $action->description_template = $request->get('description_template');
            $action->title_template = $request->get('title_template');
            $action->keywords = $request->get('keywords');

            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $action->image = $request->file('image');
            }

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
            $action = app(CourseUpdateAction::class);
            $action->id = $id;
            $action->school_id = $request->get('school_id');
            $action->name = $request->get('name');
            $action->header_template = $request->get('header_template');
            $action->text = $request->get('text');
            $action->link = $request->get('link');
            $action->url = $request->get('url');
            $action->language = $request->get('language') ? Language::from($request->get('language')) : null;
            $action->rating = $request->get('rating');
            $action->price = $request->get('price');
            $action->price_old = $request->get('price_old');
            $action->price_recurrent = $request->get('price_recurrent');
            $action->currency = $request->get('currency') ? Currency::from($request->get('currency')) : null;
            $action->online = $request->get('online');
            $action->employment = $request->get('employment');
            $action->duration = $request->get('duration');
            $action->duration_unit = $request->get('duration_unit') ? Duration::from(
                $request->get('duration_unit')
            ) : null;
            $action->lessons_amount = $request->get('lessons_amount');
            $action->modules_amount = $request->get('modules_amount');
            $action->status = Status::from($request->get('status'));

            $action->directions = $request->get('directions');
            $action->professions = $request->get('professions');
            $action->categories = $request->get('categories');
            $action->skills = $request->get('skills');
            $action->teachers = $request->get('teachers');
            $action->tools = $request->get('tools');
            $action->processes = $request->get('processes');
            $action->levels = $request->get('levels');
            $action->learns = $request->get('learns');
            $action->employments = $request->get('employments');
            $action->features = $request->get('features');

            $action->description_template = $request->get('description_template');
            $action->title_template = $request->get('title_template');
            $action->keywords = $request->get('keywords');

            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $action->image = $request->file('image');
            }

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
