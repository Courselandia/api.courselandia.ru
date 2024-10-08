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
use App\Modules\Publication\Actions\Admin\Publication\PublicationCreateAction;
use App\Modules\Publication\Actions\Admin\Publication\PublicationDestroyAction;
use App\Modules\Publication\Actions\Admin\Publication\PublicationGetAction;
use App\Modules\Publication\Actions\Admin\Publication\PublicationReadAction;
use App\Modules\Publication\Actions\Admin\Publication\PublicationUpdateAction;
use App\Modules\Publication\Actions\Admin\Publication\PublicationUpdateStatusAction;
use App\Modules\Publication\Data\Actions\Admin\PublicationCreate;
use App\Modules\Publication\Data\Actions\Admin\PublicationUpdate;
use App\Modules\Publication\Http\Requests\Admin\Publication\PublicationCreateRequest;
use App\Modules\Publication\Http\Requests\Admin\Publication\PublicationDestroyRequest;
use App\Modules\Publication\Http\Requests\Admin\Publication\PublicationReadRequest;
use App\Modules\Publication\Http\Requests\Admin\Publication\PublicationUpdateRequest;
use App\Modules\Publication\Http\Requests\Admin\Publication\PublicationUpdateStatusRequest;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Log;
use ReflectionException;
use Config;

/**
 * Класс контроллер для работы с публикациями в административной части.
 */
class PublicationController extends Controller
{
    /**
     * Получение публикации.
     *
     * @param int|string $id ID публикации.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function get(int|string $id): JsonResponse
    {
        $action = new PublicationGetAction($id);
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
     * @param PublicationReadRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ReflectionException
     */
    public function read(PublicationReadRequest $request): JsonResponse
    {
        $action = new PublicationReadAction(
            $request->get('sorts'),
            $request->get('filters'),
            $request->get('offset'),
            $request->get('limit')
        );

        $data = $action->run();

        $data['success'] = true;

        return response()->json($data);
    }

    /**
     * Добавление данных.
     *
     * @param PublicationCreateRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function create(PublicationCreateRequest $request): JsonResponse
    {
        try {
            $data = PublicationCreate::from([
                ...$request->all(),
                'published_at' => Carbon::createFromFormat(
                    'Y-m-d H:i:s O',
                    $request->get('published_at')
                )->setTimezone(Config::get('app.timezone'))
            ]);

            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $data->image = $request->file('image');
            }

            $action = new PublicationCreateAction($data);
            $data = $action->run();

            Log::info(
                trans('publication::http.controllers.admin.publicationController.create.log'),
                [
                    'module' => 'Publication',
                    'login' => Auth::getUser()->login,
                    'type' => 'create'
                ]
            );

            $data = [
                'data' => $data,
                'success' => true
            ];

            return response()->json($data);
        } catch (ValidateException $error) {
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
     * @param int|string $id ID публикации.
     * @param PublicationUpdateRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function update(int|string $id, PublicationUpdateRequest $request): JsonResponse
    {
        try {
            $data = PublicationUpdate::from([
                'id' => $id,
                ...$request->all(),
                'published_at' => Carbon::createFromFormat(
                    'Y-m-d H:i:s O',
                    $request->get('published_at')
                )->setTimezone(Config::get('app.timezone')),
            ]);

            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $data->image = $request->file('image');
            }

            $action = new PublicationUpdateAction($data);
            $data = $action->run();

            Log::info(
                trans('publication::http.controllers.admin.publicationController.update.log'),
                [
                    'module' => 'Publication',
                    'login' => Auth::getUser()->login,
                    'type' => 'update'
                ]
            );

            $data = [
                'data' => $data,
                'success' => true
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
     * Обновление статуса.
     *
     * @param int|string $id ID пользователя.
     * @param PublicationUpdateStatusRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function updateStatus(int|string $id, PublicationUpdateStatusRequest $request): JsonResponse
    {
        try {
            $action = new PublicationUpdateStatusAction($id, $request->get('status'));

            $data = $action->run();

            Log::info(trans('publication::http.controllers.admin.publicationController.update.log'), [
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
     * @param PublicationDestroyRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function destroy(PublicationDestroyRequest $request): JsonResponse
    {
        $action = new PublicationDestroyAction($request->get('ids'));
        $action->run();

        Log::info(
            trans('publication::http.controllers.admin.publicationController.destroy.log'),
            [
                'module' => 'Publication',
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
