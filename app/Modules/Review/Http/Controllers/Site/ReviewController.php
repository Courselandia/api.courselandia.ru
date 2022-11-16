<?php
/**
 * Модуль Отзывов.
 * Этот модуль содержит все классы для работы с отзывовами.
 *
 * @package App\Modules\Review
 */

namespace App\Modules\Review\Http\Controllers\Site;

use App\Modules\Review\Actions\Site\ReviewBreakDownAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Review\Actions\Site\ReviewReadAction;
use App\Modules\Review\Http\Requests\Site\ReviewReadRequest;

/**
 * Класс контроллер для работы с отзывами в публичной части.
 */
class ReviewController extends Controller
{
    /**
     * Чтение данных.
     *
     * @param ReviewReadRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException
     */
    public function read(ReviewReadRequest $request): JsonResponse
    {
        $action = app(ReviewReadAction::class);
        $action->school_id = $request->get('school_id');
        $action->offset = $request->get('offset');
        $action->limit = $request->get('limit');

        $data = $action->run();

        $data['success'] = true;

        return response()->json($data);
    }

    /**
     * Разбивка отзывов по рейтингу.
     *
     * @param int $schoolId ID школы.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException
     */
    public function breakDown(int $schoolId): JsonResponse
    {
        $action = app(ReviewBreakDownAction::class);
        $action->school_id = $schoolId;

        $data = $action->run();

        return response()->json([
            'data' => $data,
            'success' => true,
        ]);
    }
}
