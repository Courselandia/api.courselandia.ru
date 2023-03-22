<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Http\Controllers\Site;

use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Course\Actions\Site\Course\CourseDirectionReadAction;
use App\Modules\Course\Actions\Site\Course\CourseCategoryReadAction;
use App\Modules\Course\Actions\Site\Course\CourseProfessionReadAction;
use App\Modules\Course\Actions\Site\Course\CourseReadRatedAction;
use App\Modules\Course\Actions\Site\Course\CourseReadSearchAction;
use App\Modules\Course\Actions\Site\Course\CourseReadFavoritesAction;
use App\Modules\Course\Actions\Site\Course\CourseSchoolReadAction;
use App\Modules\Course\Actions\Site\Course\CourseToolReadAction;
use App\Modules\Course\Actions\Site\Course\CourseSkillReadAction;
use App\Modules\Course\Actions\Site\Course\CourseTeacherReadAction;
use App\Modules\Course\Actions\Site\Course\CourseGetAction;
use App\Modules\Course\Http\Requests\Site\Course\CourseFilterItemReadRequest;
use App\Modules\Course\Http\Requests\Site\Course\CourseReadFavoritesRequest;
use App\Modules\Course\Http\Requests\Site\Course\CourseReadRatedRequest;
use App\Modules\Course\Http\Requests\Site\Course\CourseReadRequest;
use App\Modules\Course\Http\Requests\Site\Course\CourseReadSearchRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use App\Modules\Course\Actions\Site\Course\CourseReadAction;

/**
 * Класс контроллер для работы с курсами в публичной части.
 */
class CourseController extends Controller
{
    /**
     * Получение курса.
     *
     * @param string $school Ссылка школы.
     * @param string $course Ссылка курса.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException
     */
    public function get(string $school, string $course): JsonResponse
    {
        $action = app(CourseGetAction::class);
        $action->school = $school;
        $action->course = $course;
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
     * Получение направлений.
     *
     * @param CourseFilterItemReadRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException
     */
    public function directions(CourseFilterItemReadRequest $request): JsonResponse
    {
        $action = app(CourseDirectionReadAction::class);
        $action->filters = $request->get('filters');
        $action->offset = $request->get('offset');
        $action->limit = $request->get('limit', false);
        $action->withCategories = $request->get('withCategories', false);
        $action->withCount = $request->get('withCount');

        $data = $action->run();

        $data = [
            'data' => $data,
            'success' => true,
        ];

        return response()->json($data);
    }

    /**
     * Получение профессий.
     *
     * @param CourseFilterItemReadRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException
     */
    public function professions(CourseFilterItemReadRequest $request): JsonResponse
    {
        $action = app(CourseProfessionReadAction::class);
        $action->filters = $request->get('filters');
        $action->offset = $request->get('offset');
        $action->limit = $request->get('limit');
        $action->disabled = true;

        $data = $action->run();

        $data = [
            'data' => $data,
            'success' => true,
        ];

        return response()->json($data);
    }

    /**
     * Получение категорий.
     *
     * @param CourseFilterItemReadRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException
     */
    public function categories(CourseFilterItemReadRequest $request): JsonResponse
    {
        $action = app(CourseCategoryReadAction::class);
        $action->filters = $request->get('filters');
        $action->offset = $request->get('offset');
        $action->limit = $request->get('limit');
        $action->disabled = true;

        $data = $action->run();

        $data = [
            'data' => $data,
            'success' => true,
        ];

        return response()->json($data);
    }

    /**
     * Получение школ.
     *
     * @param CourseFilterItemReadRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException
     */
    public function schools(CourseFilterItemReadRequest $request): JsonResponse
    {
        $action = app(CourseSchoolReadAction::class);
        $action->filters = $request->get('filters');
        $action->offset = $request->get('offset');
        $action->limit = $request->get('limit');
        $action->disabled = true;

        $data = $action->run();

        $data = [
            'data' => $data,
            'success' => true,
        ];

        return response()->json($data);
    }

    /**
     * Получение инструментов.
     *
     * @param CourseFilterItemReadRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException
     */
    public function tools(CourseFilterItemReadRequest $request): JsonResponse
    {
        $action = app(CourseToolReadAction::class);
        $action->filters = $request->get('filters');
        $action->offset = $request->get('offset');
        $action->limit = $request->get('limit');
        $action->disabled = true;

        $data = $action->run();

        $data = [
            'data' => $data,
            'success' => true,
        ];

        return response()->json($data);
    }

    /**
     * Получение навыков.
     *
     * @param CourseFilterItemReadRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException
     */
    public function skills(CourseFilterItemReadRequest $request): JsonResponse
    {
        $action = app(CourseSkillReadAction::class);
        $action->filters = $request->get('filters');
        $action->offset = $request->get('offset');
        $action->limit = $request->get('limit');
        $action->disabled = true;

        $data = $action->run();

        $data = [
            'data' => $data,
            'success' => true,
        ];

        return response()->json($data);
    }

    /**
     * Получение учителей.
     *
     * @param CourseFilterItemReadRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException
     */
    public function teachers(CourseFilterItemReadRequest $request): JsonResponse
    {
        $action = app(CourseTeacherReadAction::class);
        $action->filters = $request->get('filters');
        $action->offset = $request->get('offset');
        $action->limit = $request->get('limit');
        $action->disabled = true;

        $data = $action->run();

        $data = [
            'data' => $data,
            'success' => true,
        ];

        return response()->json($data);
    }

    /**
     * Получение курсы.
     *
     * @param CourseReadRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function read(CourseReadRequest $request): JsonResponse
    {
        $action = app(CourseReadAction::class);
        $action->sorts = $request->get('sorts');
        $action->filters = $request->get('filters');
        $action->offset = $request->get('offset');
        $action->section = $request->get('section');
        $action->sectionLink = $request->get('sectionLink');
        $action->limit = $request->get('limit', 36);
        $action->disabled = true;
        $action->openedSchools = $request->get('openedSchools', false);
        $action->openedCategories = $request->get('openedCategories', false);
        $action->openedProfessions = $request->get('openedProfessions', false);
        $action->openedTeachers = $request->get('openedTeachers', false);
        $action->openedSkills = $request->get('openedSkills', false);
        $action->openedTools = $request->get('openedTools', false);

        $entityCourseRead = $action->run();

        return response()->json([
            'data' => $entityCourseRead,
            'success' => true,
        ]);
    }

    /**
     * Получение лучшие курсы.
     *
     * @param CourseReadRatedRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function readRated(CourseReadRatedRequest $request): JsonResponse
    {
        $action = app(CourseReadRatedAction::class);
        $action->limit = $request->get('limit', 16);

        $data = $action->run();

        return response()->json([
            'data' => $data,
            'success' => true,
        ]);
    }

    /**
     *  Полнотекстовый поиск.
     *
     * @param CourseReadSearchRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function readSearch(CourseReadSearchRequest $request): JsonResponse
    {
        $action = app(CourseReadSearchAction::class);
        $action->limit = $request->get('limit', 12);
        $action->search = $request->get('search');

        $data = $action->run();
        $data['success'] = true;

        return response()->json($data);
    }

    /**
     * Избранное.
     *
     * @param CourseReadFavoritesRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function readFavorites(CourseReadFavoritesRequest $request): JsonResponse
    {
        if ($request->get('ids')) {
            $action = app(CourseReadFavoritesAction::class);
            $action->ids = $request->get('ids');

            $data = $action->run();
            $data['success'] = true;
        } else {
            $data = [
                'data' => [],
                'total' => 0,
                'success' => true,
            ];
        }

        return response()->json($data);
    }
}
