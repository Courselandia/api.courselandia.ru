<?php
/**
 * Модуль промоматериалов.
 * Этот модуль содержит все классы для работы с промоматериалами: промокоды и промоакции.
 *
 * @package App\Modules\Promo
 */

namespace App\Modules\Promo\Http\Controllers\Site;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use App\Modules\Promo\Actions\Site\PromoReadAction;
use App\Modules\Promo\Actions\Site\PromoLinkAction;

/**
 * Класс контроллер для работы с промоматериалами в публичной части.
 */
class PromoController extends Controller
{
    /**
     * Чтение данных.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function read(): JsonResponse
    {
        $action = new PromoReadAction();
        $data = $action->run();
        $data['success'] = true;

        return response()->json($data);
    }

    /**
     * Чтение данных.
     *
     * @param string $link Ссылка на школу.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function link(string $link): JsonResponse
    {
        $action = new PromoLinkAction($link);
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
