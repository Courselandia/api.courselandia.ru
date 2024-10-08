<?php
/**
 * Модуль Обратной связи.
 * Этот модуль содержит все классы для работы с обратной связью.
 *
 * @package App\Modules\Feedback
 */

namespace App\Modules\Feedback\Http\Controllers\Admin;

use Log;
use Auth;
use ReflectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use App\Modules\Feedback\Actions\Admin\FeedbackDestroyAction;
use App\Modules\Feedback\Actions\Admin\FeedbackGetAction;
use App\Modules\Feedback\Actions\Admin\FeedbackReadAction;
use App\Modules\Feedback\Http\Requests\Admin\FeedbackReadRequest;
use App\Modules\Feedback\Http\Requests\Admin\FeedbackDestroyRequest;

/**
 * Класс контроллер для работы с обратной связью в административной системе.
 */
class FeedbackController extends Controller
{
    /**
     * Получение обратной связи.
     *
     * @param int|string $id ID записи.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function get(int|string $id): JsonResponse
    {
        $action = new FeedbackGetAction($id);
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
     * @param FeedbackReadRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ReflectionException
     */
    public function read(FeedbackReadRequest $request): JsonResponse
    {
        $action = new FeedbackReadAction(
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
     * Удаление данных.
     *
     * @param FeedbackDestroyRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function destroy(FeedbackDestroyRequest $request): JsonResponse
    {
        $action = new FeedbackDestroyAction($request->get('ids'));
        $action->run();

        Log::info(trans('feedback::http.controllers.admin.feedbackController.destroy.log'), [
            'module' => 'Feedback',
            'login' => Auth::getUser()->login,
            'type' => 'destroy'
        ]);

        $data = [
            'success' => true
        ];

        return response()->json($data);
    }
}
