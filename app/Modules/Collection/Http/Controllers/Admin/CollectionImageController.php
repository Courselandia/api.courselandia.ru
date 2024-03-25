<?php
/**
 * Модуль Коллекций.
 * Этот модуль содержит все классы для работы с коллекциями.
 *
 * @package App\Modules\Collection
 */

namespace App\Modules\Collection\Http\Controllers\Admin;

use Auth;
use Log;
use ReflectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordExistException;
use App\Models\Exceptions\RecordNotExistException;
use App\Models\Exceptions\ValidateException;
use App\Modules\Collection\Actions\Admin\CollectionImage\CollectionImageDestroyAction;
use App\Modules\Collection\Actions\Admin\CollectionImage\CollectionImageUpdateAction;
use App\Modules\Collection\Http\Requests\Admin\CollectionImage\CollectionImageUpdateRequest;

/**
 * Класс контроллер для работы с изображениями коллекциями в административной части.
 */
class CollectionImageController extends Controller
{
    /**
     * Обновление данных.
     *
     * @param int|string $id ID пользователя.
     * @param CollectionImageUpdateRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function update(int|string $id, CollectionImageUpdateRequest $request): JsonResponse
    {
        try {
            $action = new CollectionImageUpdateAction($id, $request->file('image'));
            $teacher = $action->run();

            Log::info(trans('access::http.controllers.admin.teacherController.update.log'), [
                'module' => 'Collection',
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
            $action = new CollectionImageDestroyAction($id);
            $action->run();

            Log::info(trans('teacher::http.controllers.admin.teacherController.destroyImage.log'), [
                'module' => 'Collection',
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
