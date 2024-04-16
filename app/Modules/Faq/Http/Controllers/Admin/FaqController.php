<?php
/**
 * Модуль FAQ's.
 * Этот модуль содержит все классы для работы с FAQ's.
 *
 * @package App\Modules\Faq
 */

namespace App\Modules\Faq\Http\Controllers\Admin;

use App\Modules\Faq\Actions\Admin\FaqUpdateStatusAction;
use App\Modules\Faq\Data\FaqCreate;
use App\Modules\Faq\Data\FaqUpdate;
use App\Modules\Faq\Http\Requests\Admin\FaqUpdateStatusRequest;
use Auth;
use Log;
use App\Modules\Faq\Http\Requests\Admin\FaqCreateRequest;
use App\Modules\Faq\Http\Requests\Admin\FaqUpdateRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use App\Models\Exceptions\RecordExistException;
use App\Models\Exceptions\RecordNotExistException;
use App\Models\Exceptions\ValidateException;
use App\Modules\Faq\Actions\Admin\FaqCreateAction;
use App\Modules\Faq\Actions\Admin\FaqDestroyAction;
use App\Modules\Faq\Actions\Admin\FaqGetAction;
use App\Modules\Faq\Actions\Admin\FaqReadAction;
use App\Modules\Faq\Actions\Admin\FaqUpdateAction;
use App\Modules\Faq\Http\Requests\Admin\FaqDestroyRequest;
use App\Modules\Faq\Http\Requests\Admin\FaqReadRequest;

/**
 * Класс контроллер для работы с FAQ's в административной части.
 */
class FaqController extends Controller
{
    /**
     * Получение FAQ.
     *
     * @param int|string $id ID FAQ.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function get(int|string $id): JsonResponse
    {
        $action = new FaqGetAction($id);
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
     * @param FaqReadRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function read(FaqReadRequest $request): JsonResponse
    {
        $action = new FaqReadAction(
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
     * @param FaqCreateRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function create(FaqCreateRequest $request): JsonResponse
    {
        try {
            $data = FaqCreate::from($request->all());
            $action = new FaqCreateAction($data);
            $data = $action->run();

            Log::info(
                trans('faq::http.controllers.admin.faqController.create.log'),
                [
                    'module' => 'Faq',
                    'login' => Auth::getUser()->login,
                    'type' => 'create'
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
        }
    }

    /**
     * Обновление данных.
     *
     * @param int|string $id ID FAQ.
     * @param FaqUpdateRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function update(int|string $id, FaqUpdateRequest $request): JsonResponse
    {
        try {
            $data = FaqUpdate::from([
                ...$request->all(),
                'id' => $id,
            ]);
            $action = new FaqUpdateAction($data);
            $data = $action->run();

            Log::info(
                trans('faq::http.controllers.admin.faqController.update.log'),
                [
                    'module' => 'Faq',
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
     * @param FaqUpdateStatusRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function updateStatus(int|string $id, FaqUpdateStatusRequest $request): JsonResponse
    {
        try {
            $action = new FaqUpdateStatusAction($id, $request->get('status'));
            $data = $action->run();

            Log::info(trans('faq::http.controllers.admin.faqController.update.log'), [
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
     * @param FaqDestroyRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function destroy(FaqDestroyRequest $request): JsonResponse
    {
        $action = new FaqDestroyAction($request->get('ids'));
        $action->run();

        Log::info(
            trans('faq::http.controllers.admin.faqController.destroy.log'),
            [
                'module' => 'Faq',
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
