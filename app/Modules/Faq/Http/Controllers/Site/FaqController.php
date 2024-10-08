<?php
/**
 * Модуль FAQ's.
 * Этот модуль содержит все классы для работы с FAQ's.
 *
 * @package App\Modules\Faq
 */

namespace App\Modules\Faq\Http\Controllers\Site;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use App\Modules\Faq\Actions\Site\FaqReadAction;

/**
 * Класс контроллер для работы с FAQ's в публичной части.
 */
class FaqController extends Controller
{
    /**
     * Чтение данных.
     *
     * @param string $school ID школы.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function read(string $school): JsonResponse
    {
        $action = new FaqReadAction($school);
        $data = $action->run();

        $data['success'] = true;

        return response()->json($data);
    }
}
