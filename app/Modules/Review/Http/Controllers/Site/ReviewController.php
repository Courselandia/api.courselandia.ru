<?php
/**
 * Модуль Отзывов.
 * Этот модуль содержит все классы для работы с отзывами.
 *
 * @package App\Modules\Review
 */

namespace App\Modules\Review\Http\Controllers\Site;

use App\Modules\Review\Actions\Site\ReviewBreakDownAction;
use App\Modules\Review\Data\Site\ReviewRead;
use App\Modules\Review\Values\ReviewBreakDown;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
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
     */
    public function read(ReviewReadRequest $request): JsonResponse
    {
        $data = ReviewRead::from($request->all());
        $action = new ReviewReadAction($data);
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
     */
    public function breakDown(int $schoolId): JsonResponse
    {
        $action = new ReviewBreakDownAction($schoolId);
        $data = $action->run();

        return response()->json([
            'data' => collect($data)->map(function ($item) {
                /**
                 * @var ReviewBreakDown $item
                 */
                return [
                    'rating' => $item->getRating(),
                    'amount' => $item->getAmount(),
                ];
            }),
            'success' => true,
        ]);
    }
}
