<?php
/**
 * Модуль Разделов.
 * Этот модуль содержит все классы для работы с разделами каталога.
 *
 * @package App\Modules\Section
 */

namespace App\Modules\Section\Http\Controllers\Site;

use App\Modules\Section\Actions\Site\SectionLinkAction;
use App\Modules\Section\Http\Requests\Site\SectionLinkRequest;
use App\Modules\Salary\Enums\Level;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

/**
 * Класс контроллер для работы с разделами в публичной части.
 */
class SectionController extends Controller
{
    /**
     * Получение раздела.
     *
     * @param SectionLinkRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function link(SectionLinkRequest $request): JsonResponse
    {
        $action = new SectionLinkAction(
            $request->get('items'),
            $request->get('level') ? Level::from($request->get('level')) : null,
            $request->get('free', false),
        );
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
}
