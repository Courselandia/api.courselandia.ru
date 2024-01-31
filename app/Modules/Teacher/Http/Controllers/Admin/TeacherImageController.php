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
use App\Modules\Teacher\Actions\Admin\TeacherImage\TeacherImageDestroyAction;
use App\Modules\Teacher\Actions\Admin\TeacherImage\TeacherImageUpdateAction;
use App\Modules\Teacher\Http\Requests\Admin\TeacherImage\TeacherImageUpdateRequest;
use Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Log;
use ReflectionException;

/**
 * Класс контроллер для работы с изображениями учителя в административной части.
 */
class TeacherImageController extends Controller
{
    /**
     * Обновление данных.
     *
     * @param int|string $id ID пользователя.
     * @param TeacherImageUpdateRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function update(int|string $id, TeacherImageUpdateRequest $request): JsonResponse
    {
        try {
            $action = new TeacherImageUpdateAction($id, $request->file('image'));
            $teacher = $action->run();

            Log::info(trans('access::http.controllers.admin.teacherController.update.log'), [
                'module' => 'Teacher',
                'login' => Auth::getUser()->login,
                'type' => 'update'
            ]);

            $data = [
                'success' => true,
                'data' => $teacher
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
     * @param int|string $id ID шаблона страницы.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException|ReflectionException
     */
    public function destroy(int|string $id): JsonResponse
    {
        try {
            $action = new TeacherImageDestroyAction($id);
            $action->run();

            Log::info(trans('teacher::http.controllers.admin.teacherController.destroyImage.log'), [
                'module' => 'Teacher',
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
