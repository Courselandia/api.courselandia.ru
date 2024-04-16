<?php
/**
 * Модуль Учителей.
 * Этот модуль содержит все классы для работы с учителями.
 *
 * @package App\Modules\Teacher
 */

namespace App\Modules\Teacher\Http\Controllers\Admin;

use App\Modules\Teacher\Data\TeacherCreate;
use App\Modules\Teacher\Data\TeacherExperience;
use App\Modules\Teacher\Data\TeacherSocialMedia;
use App\Modules\Teacher\Data\TeacherUpdate;
use Auth;
use Carbon\Carbon;
use Log;
use Throwable;
use ReflectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use App\Models\Exceptions\RecordExistException;
use App\Models\Exceptions\RecordNotExistException;
use App\Models\Exceptions\ValidateException;
use App\Modules\Course\Actions\Admin\Course\CourseReadAction;
use App\Modules\Metatag\Template\TemplateException;
use App\Modules\Teacher\Actions\Admin\Teacher\TeacherCreateAction;
use App\Modules\Teacher\Actions\Admin\Teacher\TeacherDestroyAction;
use App\Modules\Teacher\Actions\Admin\Teacher\TeacherDetachCoursesAction;
use App\Modules\Teacher\Actions\Admin\Teacher\TeacherGetAction;
use App\Modules\Teacher\Actions\Admin\Teacher\TeacherReadAction;
use App\Modules\Teacher\Actions\Admin\Teacher\TeacherUpdateAction;
use App\Modules\Teacher\Actions\Admin\Teacher\TeacherUpdateStatusAction;
use App\Modules\Teacher\Http\Requests\Admin\Teacher\TeacherCreateRequest;
use App\Modules\Teacher\Http\Requests\Admin\Teacher\TeacherDestroyRequest;
use App\Modules\Teacher\Http\Requests\Admin\Teacher\TeacherDetachCoursesRequest;
use App\Modules\Teacher\Http\Requests\Admin\Teacher\TeacherReadRequest;
use App\Modules\Teacher\Http\Requests\Admin\Teacher\TeacherUpdateRequest;
use App\Modules\Teacher\Http\Requests\Admin\Teacher\TeacherUpdateStatusRequest;

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
     */
    public function get(int|string $id): JsonResponse
    {
        $action = new TeacherGetAction($id);
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
     * @throws ReflectionException
     */
    public function read(TeacherReadRequest $request): JsonResponse
    {
        $action = new TeacherReadAction(
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
     * Получение курсов учителя.
     *
     * @param int|string $id ID учителя.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function courses(int|string $id): JsonResponse
    {
        $action = new CourseReadAction(null, null, null, null, $id);
        $data = $action->run();
        $data['success'] = true;

        return response()->json($data);
    }

    /**
     * Отсоединение курсов от учителя.
     *
     * @param int|string $id ID учителя.
     * @param TeacherDetachCoursesRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function detachCourses(int|string $id, TeacherDetachCoursesRequest $request): JsonResponse
    {
        $action = new TeacherDetachCoursesAction($id, $request->get('ids'));
        $action->run();

        Log::info(
            trans('teacher::http.controllers.admin.teacherController.detachCourses.log'),
            [
                'module' => 'Teacher',
                'login' => Auth::getUser()->login,
                'type' => 'detach'
            ]
        );

        $data = [
            'success' => true
        ];

        return response()->json($data);
    }

    /**
     * Добавление данных.
     *
     * @param TeacherCreateRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws Throwable
     */
    public function create(TeacherCreateRequest $request): JsonResponse
    {
        try {
            $data = TeacherCreate::from([
                ...$request->all(),
                'socialMedias' => TeacherSocialMedia::collect(collect($request->get('socialMedias'))
                    ->map(static function ($socialMedia) {
                        return TeacherSocialMedia::from($socialMedia);
                    })
                    ->toArray()),
                'experiences' => TeacherExperience::collect(collect($request->get('experiences'))
                    ->map(static function ($experience) {
                        return TeacherExperience::from([
                            ...$experience,
                            'weight' => $experience['weight'] ?? 0,
                            'started' => (isset($experience['started']) && $experience['started']) ? Carbon::createFromFormat(
                                'Y-m-d',
                                $experience['started']
                            ) : null,
                            'finished' => (isset($experience['finished']) && $experience['finished']) ? Carbon::createFromFormat(
                                'Y-m-d',
                                $experience['finished']
                            ) : null,
                        ]);
                    })
                    ->toArray()
                ),
            ]);

            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $data->image = $request->file('image');
            }

            if ($request->get('imageCropped')) {
                $data->imageCropped = $request->get('imageCropped');
                $data->imageCroppedOptions = $request->get('imageCroppedOptions');
            }

            $action = new TeacherCreateAction($data);
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
     * @param int|string $id ID учителя.
     * @param TeacherUpdateRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws Throwable
     */
    public function update(int|string $id, TeacherUpdateRequest $request): JsonResponse
    {
        try {
            $data = TeacherUpdate::from([
                ...$request->all(),
                'id' => $id,
                'socialMedias' => TeacherSocialMedia::collect(collect($request->get('socialMedias'))
                    ->map(static function ($socialMedia) {
                        return TeacherSocialMedia::from($socialMedia);
                    })
                    ->toArray()),
                'experiences' => TeacherExperience::collect(collect($request->get('experiences'))
                    ->map(static function ($experience) {
                        return TeacherExperience::from([
                            ...$experience,
                            'weight' => $experience['weight'] ?? 0,
                            'started' => (isset($experience['started']) && $experience['started']) ? Carbon::createFromFormat(
                                'Y-m-d',
                                $experience['started']
                            ) : null,
                            'finished' => (isset($experience['finished']) && $experience['finished']) ? Carbon::createFromFormat(
                                'Y-m-d',
                                $experience['finished']
                            ) : null,
                        ]);
                    })
                    ->toArray()
                ),
            ]);

            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $data->image = $request->file('image');
            }

            if ($request->get('imageCropped')) {
                $data->imageCropped = $request->get('imageCropped');
                $data->imageCroppedOptions = $request->get('imageCroppedOptions');
            }

            $action = new TeacherUpdateAction($data);
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
     * @param TeacherUpdateStatusRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function updateStatus(int|string $id, TeacherUpdateStatusRequest $request): JsonResponse
    {
        try {
            $action = new TeacherUpdateStatusAction($id, $request->get('status'));
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
        $action = new TeacherDestroyAction($request->get('ids'));
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
