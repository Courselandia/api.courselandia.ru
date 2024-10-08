<?php
/**
 * Модуль ядра системы.
 * Этот модуль содержит все классы для работы с ядром системы.
 *
 * @package App\Modules\Core
 */

namespace App\Modules\Core\Http\Controllers\Admin;

use Log;
use Typography;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Routing\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Modules\Core\Actions\Admin\CacheFlushAction;

/**
 * Класс контроллер для работы с ядром дополнительных возможностей.
 */
class CoreController extends Controller
{
    /**
     * Удаление кеша.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws GuzzleException
     */
    public function clean(): JsonResponse
    {
        $action = new CacheFlushAction();
        $action->run();

        Log::info(trans('core::http.controllers.admin.coreController.clean.log'), [
            'module' => 'Cache',
            'type' => 'destroy'
        ]);

        return response()->json([
            'success' => true,
            'data' => null,
        ]);
    }

    /**
     * Типограф.
     *
     * @param  Request  $request  Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function typography(Request $request): JsonResponse
    {
        $text = Typography::process($request->get('text'));

        $data = [
            'success' => true,
            'text' => $text
        ];

        return response()->json($data)->setStatusCode(200);
    }
}
