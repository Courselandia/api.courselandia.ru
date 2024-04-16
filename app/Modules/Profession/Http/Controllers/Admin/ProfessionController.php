<?php
/**
 * Модуль Профессии.
 * Этот модуль содержит все классы для работы с профессиями.
 *
 * @package App\Modules\Profession
 */

namespace App\Modules\Profession\Http\Controllers\Admin;

use App\Models\Exceptions\RecordExistException;
use App\Models\Exceptions\RecordNotExistException;
use App\Models\Exceptions\ValidateException;
use App\Modules\Metatag\Template\TemplateException;
use App\Modules\Profession\Actions\Admin\ProfessionCreateAction;
use App\Modules\Profession\Actions\Admin\ProfessionDestroyAction;
use App\Modules\Profession\Actions\Admin\ProfessionGetAction;
use App\Modules\Profession\Actions\Admin\ProfessionReadAction;
use App\Modules\Profession\Actions\Admin\ProfessionUpdateAction;
use App\Modules\Profession\Actions\Admin\ProfessionUpdateStatusAction;
use App\Modules\Profession\Data\ProfessionCreate;
use App\Modules\Profession\Data\ProfessionUpdate;
use App\Modules\Profession\Http\Requests\Admin\ProfessionCreateRequest;
use App\Modules\Profession\Http\Requests\Admin\ProfessionDestroyRequest;
use App\Modules\Profession\Http\Requests\Admin\ProfessionReadRequest;
use App\Modules\Profession\Http\Requests\Admin\ProfessionUpdateRequest;
use App\Modules\Profession\Http\Requests\Admin\ProfessionUpdateStatusRequest;
use Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Log;
use ReflectionException;

/**
 * Класс контроллер для работы с профессиями в административной части.
 */
class ProfessionController extends Controller
{
    /**
     * Получение профессии.
     *
     * @param int|string $id ID профессии.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function get(int|string $id): JsonResponse
    {
        $action = new ProfessionGetAction($id);
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
     * @param ProfessionReadRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ReflectionException
     */
    public function read(ProfessionReadRequest $request): JsonResponse
    {
        $action = new ProfessionReadAction(
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
     * Добавление данных.
     *
     * @param ProfessionCreateRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function create(ProfessionCreateRequest $request): JsonResponse
    {
        try {
            $data = ProfessionCreate::from($request->all());
            $action = new ProfessionCreateAction($data);
            $data = $action->run();

            Log::info(
                trans('profession::http.controllers.admin.professionController.create.log'),
                [
                    'module' => 'Profession',
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
     * @param int|string $id ID профессии.
     * @param ProfessionUpdateRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function update(int|string $id, ProfessionUpdateRequest $request): JsonResponse
    {
        try {
            $data = ProfessionUpdate::from([
                'id' => $id,
                ...$request->all(),
            ]);
            $action = new ProfessionUpdateAction($data);
            $data = $action->run();

            Log::info(
                trans('profession::http.controllers.admin.professionController.update.log'),
                [
                    'module' => 'Profession',
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
     * @param ProfessionUpdateStatusRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function updateStatus(int|string $id, ProfessionUpdateStatusRequest $request): JsonResponse
    {
        try {
            $action = new ProfessionUpdateStatusAction($id, $request->get('status'));
            $data = $action->run();

            Log::info(trans('profession::http.controllers.admin.professionController.update.log'), [
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
     * @param ProfessionDestroyRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function destroy(ProfessionDestroyRequest $request): JsonResponse
    {
        $action = new ProfessionDestroyAction($request->get('ids'));
        $action->run();

        Log::info(
            trans('profession::http.controllers.admin.professionController.destroy.log'),
            [
                'module' => 'Profession',
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
