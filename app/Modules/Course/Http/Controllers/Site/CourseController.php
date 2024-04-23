<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Http\Controllers\Site;

use App\Modules\Course\Actions\Site\Course\CourseDirectionReadAction;
use App\Modules\Course\Actions\Site\Course\CourseCategoryReadAction;
use App\Modules\Course\Actions\Site\Course\CourseProfessionReadAction;
use App\Modules\Course\Actions\Site\Course\CourseReadRatedAction;
use App\Modules\Course\Actions\Site\Course\CourseReadSearchAction;
use App\Modules\Course\Actions\Site\Course\CourseReadFavoritesAction;
use App\Modules\Course\Actions\Site\Course\CourseSchoolReadAction;
use App\Modules\Course\Actions\Site\Course\CourseStatAction;
use App\Modules\Course\Actions\Site\Course\CourseToolReadAction;
use App\Modules\Course\Actions\Site\Course\CourseSkillReadAction;
use App\Modules\Course\Actions\Site\Course\CourseTeacherReadAction;
use App\Modules\Course\Actions\Site\Course\CourseGetAction;
use App\Modules\Course\Entities\CourseRead;
use App\Modules\Course\Helpers\CleanCourseList;
use App\Modules\Course\Helpers\CleanCourseRead;
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
     * @param string $link Ссылка курса.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function get(string $school, string $link): JsonResponse
    {
        $action = new CourseGetAction($school, $link);
        $data = $action->run();

        if ($data->course) {
            $data = [
                'data' => $data,
                'success' => true,
            ];

            return response()->json($data);
        }

        $data = [
            'data' => null,
            'success' => false,
        ];

        return response()->json($data)->setStatusCode(404);
    }

    /**
     * Получение направлений.
     *
     * @param CourseFilterItemReadRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function directions(CourseFilterItemReadRequest $request): JsonResponse
    {
        $action = new CourseDirectionReadAction(
            $request->get('filters'),
            $request->get('offset'),
            $request->get('limit', false),
            $request->get('withCategories', false),
            $request->get('withCount'),
        );

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
     */
    public function professions(CourseFilterItemReadRequest $request): JsonResponse
    {
        $action = new CourseProfessionReadAction(
            $request->get('filters'),
            $request->get('offset'),
            $request->get('limit'),
            true,
        );


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
     */
    public function categories(CourseFilterItemReadRequest $request): JsonResponse
    {
        $action = new CourseCategoryReadAction(
            $request->get('filters'),
            $request->get('offset'),
            $request->get('limit'),
            true,
        );

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
     */
    public function schools(CourseFilterItemReadRequest $request): JsonResponse
    {
        $action = new CourseSchoolReadAction(
            $request->get('filters'),
            $request->get('offset'),
            $request->get('limit'),
            true,
        );

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
     */
    public function tools(CourseFilterItemReadRequest $request): JsonResponse
    {
        $action = new CourseToolReadAction(
            $request->get('filters'),
            $request->get('offset'),
            $request->get('limit'),
            true,
        );

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
     */
    public function skills(CourseFilterItemReadRequest $request): JsonResponse
    {
        $action = new CourseSkillReadAction(
            $request->get('filters'),
            $request->get('offset'),
            $request->get('limit'),
            true,
        );

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
     */
    public function teachers(CourseFilterItemReadRequest $request): JsonResponse
    {
        $action = new CourseTeacherReadAction(
            $request->get('filters'),
            $request->get('offset'),
            $request->get('limit'),
            true,
        );

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
        $data = CourseRead::from([
            ...$request->toArray(),
            'disabled' => true,
            'openedSchools' => $request->get('openedSchools', false),
            'openedCategories' => $request->get('openedCategories', false),
            'openedProfessions' => $request->get('openedProfessions', false),
            'openedTeachers' => $request->get('openedTeachers', false),
            'openedSkills' => $request->get('openedSkills', false),
            'openedTools' => $request->get('openedTools', false),
        ]);
        $action = new CourseReadAction($data);
        $entityCourseRead = $action->run();
        $data = CleanCourseRead::do($entityCourseRead->toArray());

        return response()->json([
            'data' => $data,
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
        $action = new CourseReadRatedAction($request->get('limit', 16));
        $data = $action->run();
        $data = CleanCourseList::do($data);

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
        $action = new CourseReadSearchAction($request->get('limit', 12), $request->get('search'));
        $data = $action->run();
        $data['data'] = CleanCourseList::do($data['data']);
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
            $action = new CourseReadFavoritesAction($request->get('ids'));
            $data = $action->run();
            $data['data'] = CleanCourseList::do($data['data']);
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

    /**
     * Получение статистики.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function stat(): JsonResponse
    {
        $action = new CourseStatAction();
        $stat = $action->run();

        return response()->json([
            'amountCourses' => $stat->getAmountCourses(),
            'amountSchools' => $stat->getAmountSchools(),
            'amountTeachers' => $stat->getAmountTeachers(),
            'amountReviews' => $stat->getAmountReviews(),
        ]);
    }
}
