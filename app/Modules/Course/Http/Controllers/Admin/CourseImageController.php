<?php
/**
 * Модуль Публикации.
 * Этот модуль содержит все классы для работы с публикациями.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Http\Controllers\Admin;

use Auth;
use Log;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use ReflectionException;
use App\Models\Exceptions\RecordExistException;
use App\Models\Exceptions\RecordNotExistException;
use App\Models\Exceptions\ValidateException;
use App\Modules\Course\Actions\Admin\CourseImage\CourseImageDestroyAction;
use App\Modules\Course\Actions\Admin\CourseImage\CourseImageUpdateAction;
use App\Modules\Course\Http\Requests\Admin\CourseImage\CourseImageUpdateRequest;

/**
 * Класс контроллер для работы с изображениями курсов в административной части.
 */
class CourseImageController extends Controller
{
    /**
     * Обновление данных.
     *
     * @param int|string $id ID курса.
     * @param CourseImageUpdateRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function update(int|string $id, CourseImageUpdateRequest $request): JsonResponse
    {
        try {
            $action = new CourseImageUpdateAction($id, $request->file('image'));
            $course = $action->run();

            Log::info(trans('course::http.controllers.admin.courseController.update.log'), [
                'module' => 'Course',
                'login' => Auth::getUser()->login,
                'type' => 'update'
            ]);

            $data = [
                'success' => true,
                'data' => $course
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
     * Удаление изображения.
     *
     * @param int|string $id ID курса.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ReflectionException
     */
    public function destroy(int|string $id): JsonResponse
    {
        try {
            $action = new CourseImageDestroyAction($id);
            $action->run();

            Log::info(trans('course::http.controllers.admin.courseController.destroyImage.log'), [
                'module' => 'Course',
                'login' => Auth::getUser()->login,
                'type' => 'destroy'
            ]);

            $data = [
                'success' => true
            ];

            return response()->json($data);
        } catch (RecordNotExistException $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage()
            ])->setStatusCode(404);
        }
    }
}
