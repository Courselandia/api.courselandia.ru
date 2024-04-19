<?php
/**
 * Модуль Термином.
 * Этот модуль содержит все классы для работы с терминами.
 *
 * @package App\Modules\Term
 */

namespace App\Modules\Term\Http\Controllers\Admin;

use Auth;
use Log;
use ReflectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use App\Modules\Term\Data\TermCreate;
use App\Modules\Term\Data\TermUpdate;
use App\Models\Exceptions\RecordExistException;
use App\Models\Exceptions\RecordNotExistException;
use App\Models\Exceptions\ValidateException;
use App\Modules\Metatag\Template\TemplateException;
use App\Modules\Term\Actions\Admin\TermCreateAction;
use App\Modules\Term\Actions\Admin\TermDestroyAction;
use App\Modules\Term\Actions\Admin\TermGetAction;
use App\Modules\Term\Actions\Admin\TermReadAction;
use App\Modules\Term\Actions\Admin\TermUpdateAction;
use App\Modules\Term\Actions\Admin\TermUpdateStatusAction;
use App\Modules\Term\Http\Requests\Admin\TermCreateRequest;
use App\Modules\Term\Http\Requests\Admin\TermDestroyRequest;
use App\Modules\Term\Http\Requests\Admin\TermReadRequest;
use App\Modules\Term\Http\Requests\Admin\TermUpdateRequest;
use App\Modules\Term\Http\Requests\Admin\TermUpdateStatusRequest;

/**
 * Класс контроллер для работы с терминами в административной части.
 */
class TermController extends Controller
{
    /**
     * Получение термина.
     *
     * @param int|string $id ID термина.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function get(int|string $id): JsonResponse
    {
        $action = new TermGetAction($id);
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
     * @param TermReadRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ReflectionException
     */
    public function read(TermReadRequest $request): JsonResponse
    {
        $action = new TermReadAction(
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
     * @param TermCreateRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function create(TermCreateRequest $request): JsonResponse
    {
        try {
            $data = TermCreate::from($request->toArray());
            $action = new TermCreateAction($data);
            $data = $action->run();

            Log::info(
                trans('term::http.controllers.admin.termController.create.log'),
                [
                    'module' => 'Term',
                    'login' => Auth::getUser()->login,
                    'type' => 'create',
                ],
            );

            $data = [
                'data' => $data,
                'success' => true,
            ];

            return response()->json($data);
        } catch (ValidateException|TemplateException $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage(),
            ])->setStatusCode(400);
        } catch (RecordExistException $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage(),
            ])->setStatusCode(404);
        }
    }

    /**
     * Обновление данных.
     *
     * @param int|string $id ID термина.
     * @param TermUpdateRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function update(int|string $id, TermUpdateRequest $request): JsonResponse
    {
        try {
            $data = TermUpdate::from([
                ...$request->toArray(),
                'id' => $id,
            ]);
            $action = new TermUpdateAction($data);
            $data = $action->run();

            Log::info(
                trans('term::http.controllers.admin.termController.update.log'),
                [
                    'module' => 'Term',
                    'login' => Auth::getUser()->login,
                    'type' => 'update',
                ],
            );

            $data = [
                'data' => $data,
                'success' => true,
            ];

            return response()->json($data);
        } catch (ValidateException|RecordExistException|TemplateException $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage(),
            ])->setStatusCode(400);
        } catch (RecordNotExistException $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage(),
            ])->setStatusCode(404);
        }
    }

    /**
     * Обновление статуса.
     *
     * @param int|string $id ID термина.
     * @param TermUpdateStatusRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function updateStatus(int|string $id, TermUpdateStatusRequest $request): JsonResponse
    {
        try {
            $action = new TermUpdateStatusAction($id, $request->get('status'));
            $data = $action->run();

            Log::info(trans('term::http.controllers.admin.termController.update.log'), [
                'module' => 'User',
                'login' => Auth::getUser()->login,
                'type' => 'update',
            ]);

            $data = [
                'success' => true,
                'data' => $data,
            ];

            return response()->json($data);
        } catch (ValidateException|RecordExistException $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage(),
            ])->setStatusCode(400);
        } catch (RecordNotExistException $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage(),
            ])->setStatusCode(404);
        }
    }

    /**
     * Удаление данных.
     *
     * @param TermDestroyRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function destroy(TermDestroyRequest $request): JsonResponse
    {
        $action = new TermDestroyAction($request->get('ids'));
        $action->run();

        Log::info(
            trans('term::http.controllers.admin.termController.destroy.log'),
            [
                'module' => 'Term',
                'login' => Auth::getUser()->login,
                'type' => 'destroy',
            ]
        );

        $data = [
            'success' => true,
        ];

        return response()->json($data);
    }
}
