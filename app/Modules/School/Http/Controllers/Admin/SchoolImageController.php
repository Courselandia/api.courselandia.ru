<?php
/**
 * Модуль Школ.
 * Этот модуль содержит все классы для работы со школами.
 *
 * @package App\Modules\School
 */

namespace App\Modules\School\Http\Controllers\Admin;

use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordExistException;
use App\Models\Exceptions\RecordNotExistException;
use App\Models\Exceptions\ValidateException;
use App\Modules\School\Actions\Admin\SchoolImage\SchoolImageDestroyAction;
use App\Modules\School\Actions\Admin\SchoolImage\SchoolImageUpdateAction;
use App\Modules\School\Http\Requests\Admin\SchoolImage\SchoolImageUpdateRequest;
use App\Modules\School\Http\Requests\Admin\SchoolImage\SchoolImageDestroyRequest;
use Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Log;
use ReflectionException;

/**
 * Класс контроллер для работы с изображениями школ в административной части.
 */
class SchoolImageController extends Controller
{
    /**
     * Обновление данных.
     *
     * @param  int|string  $id  ID пользователя.
     * @param  SchoolImageUpdateRequest  $request  Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException
     * @throws ReflectionException
     */
    public function update(int|string $id, SchoolImageUpdateRequest $request): JsonResponse
    {
        try {
            $action = app(SchoolImageUpdateAction::class);
            $action->id = $id;
            $action->image = $request->file('image');
            $action->type = $request->get('type');

            $school = $action->run();

            Log::info(trans('access::http.controllers.admin.schoolController.update.log'), [
                'module' => 'School',
                'login' => Auth::getUser()->login,
                'type' => 'update'
            ]);

            $data = [
                'success' => true,
                'data' => $school
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
     * @param  int|string  $id  ID шаблона страницы.
     * @param  SchoolImageDestroyRequest  $request  Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException|ReflectionException
     */
    public function destroy(int|string $id, SchoolImageDestroyRequest $request): JsonResponse
    {
        try {
            $action = app(SchoolImageDestroyAction::class);
            $action->id = $id;
            $action->type = $request->get('type');
            $action->run();

            Log::info(trans('school::http.controllers.admin.schoolController.destroyImage.log'), [
                'module' => 'School',
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
