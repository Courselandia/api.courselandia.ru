<?php
/**
 * Модуль Обратной связи.
 * Этот модуль содержит все классы для работы с обратной связью.
 *
 * @package App\Modules\Feedback
 */

namespace App\Modules\Feedback\Http\Controllers\Site;

use Log;
use Act;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use App\Modules\Feedback\Actions\Site\FeedbackSendAction;
use App\Modules\Feedback\Http\Requests\Site\FeedbackSendRequest;

/**
 * Класс контроллер для работы с обратной связью в публичной части сайта.
 */
class FeedbackController extends Controller
{
    /**
     * Добавление данных.
     *
     * @param FeedbackSendRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function send(FeedbackSendRequest $request): JsonResponse
    {
        $action = new FeedbackSendAction(
            $request->get('name'),
            $request->get('email'),
            $request->get('message'),
            $request->get('phone')
        );

        $data = $action->run();
        Act::add('feedback');

        Log::info(trans('feedback::http.controllers.site.feedbackController.send.log'), [
            'module' => 'Feedback',
            'type' => 'create'
        ]);

        $data = [
            'success' => true,
            'data' => $data
        ];

        return response()->json($data);
    }
}
