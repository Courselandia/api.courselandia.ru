<?php
/**
 * Модуль Публикации.
 * Этот модуль содержит все классы для работы с публикациями.
 *
 * @package App\Modules\Publication
 */

namespace App\Modules\Publication\Http\Controllers\Admin;

use App\Models\Exceptions\RecordExistException;
use App\Models\Exceptions\RecordNotExistException;
use App\Models\Exceptions\ValidateException;
use App\Modules\Publication\Actions\Admin\PublicationImage\PublicationImageDestroyAction;
use App\Modules\Publication\Actions\Admin\PublicationImage\PublicationImageUpdateAction;
use App\Modules\Publication\Http\Requests\Admin\PublicationImage\PublicationImageUpdateRequest;
use Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Log;
use ReflectionException;


/**
 * Класс контроллер для работы с изображениями публикаций в административной части.
 */
class PublicationImageController extends Controller
{
    /**
     * Обновление данных.
     *
     * @param  int|string  $id  ID пользователя.
     * @param  PublicationImageUpdateRequest  $request  Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function update(int|string $id, PublicationImageUpdateRequest $request): JsonResponse
    {
        try {
            $action = new PublicationImageUpdateAction($id, $request->file('image'));
            $publication = $action->run();

            Log::info(trans('access::http.controllers.admin.publicationController.update.log'), [
                'module' => 'Publication',
                'login' => Auth::getUser()->login,
                'type' => 'update'
            ]);

            $data = [
                'success' => true,
                'data' => $publication
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
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ReflectionException
     */
    public function destroy(int|string $id): JsonResponse
    {
        try {
            $action = new PublicationImageDestroyAction($id);
            $action->run();

            Log::info(trans('publication::http.controllers.admin.publicationController.destroyImage.log'), [
                'module' => 'Publication',
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
