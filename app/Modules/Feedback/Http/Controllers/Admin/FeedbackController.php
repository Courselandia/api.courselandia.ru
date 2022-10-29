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

use App\Models\Exceptions\ParameterInvalidException;

/**
 * Класс контроллер для работы с обратной связью в административной системе.
 */
class FeedbackController extends Controller
{
    /**
     * Получение обратной связи.
     *
     * @param  int|string  $id  ID записи.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException
     * @throws ReflectionException
     */
    public function get(int|string $id): JsonResponse
    {
        $action = app(FeedbackGetAction::class);
        $action->id = $id;

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
     * @param  FeedbackReadRequest  $request  Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException|ReflectionException
     */
    public function read(FeedbackReadRequest $request): JsonResponse
    {
        $action = app(FeedbackReadAction::class);
        $action->filters = $request->get('filters');
        $action->sorts = $request->get('sorts');
        $action->offset = $request->get('offset');
        $action->limit = $request->get('limit');

        $data = $action->run();

        $data['success'] = true;

        return response()->json($data);
    }

    /**
     * Удаление данных.
     *
     * @param  FeedbackDestroyRequest  $request  Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function destroy(FeedbackDestroyRequest $request): JsonResponse
    {
        $action = app(FeedbackDestroyAction::class);
        $action->ids = json_decode($request->get('ids'), true);
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
