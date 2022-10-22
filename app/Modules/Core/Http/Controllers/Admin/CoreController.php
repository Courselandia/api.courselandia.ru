<?php
/**
 * Модуль ядра системы.
 * Этот модуль содержит все классы для работы с ядром системы.
 *
 * @package App\Modules\Core
 */

namespace App\Modules\Core\Http\Controllers\Admin;

use Auth;
use Cache;
use Artisan;
use Log;
use EMT\EMTypograph;
use Illuminate\Routing\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Класс контроллер для работы с ядром дополнительных возможностей.
 */
class CoreController extends Controller
{
    /**
     * Удаление кеша.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function clean(): JsonResponse
    {
        $login = Auth::getUser()->login;

        Cache::flush();

        Artisan::call('view:clear');
        Artisan::call('config:cache');

        Log::info(trans('core::http.controllers.admin.coreController.clean.log'), [
            'login' => $login,
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
        $text = str_replace("\t", '', $request->get('text'));
        $text = str_replace("\n\r", '', $text);
        $text = str_replace("\r\n", '', $text);
        $text = str_replace("\n", '', $text);
        $text = str_replace("\r", '', $text);

        if ($text !== '') {
            $typograph = new EMTypograph();

            $typograph->do_setup('OptAlign.all', false);
            $typograph->do_setup('Text.paragraphs', false);
            $typograph->do_setup('Text.breakline', false);

            $result = $typograph->process($text);

            if ($result) {
                $data = [
                    'success' => true,
                    'text' => $result
                ];
            } else {
                $data = [
                    'success' => false,
                    'data' => null,
                ];
            }
        } else {
            $data = [
                'success' => true,
                'text' => ''
            ];
        }

        return response()->json($data)->setStatusCode($data['success'] == true ? 200 : 400);
    }
}
