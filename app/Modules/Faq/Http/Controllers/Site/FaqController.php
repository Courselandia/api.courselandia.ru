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
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Faq\Actions\Site\FaqReadAction;

/**
 * Класс контроллер для работы с FAQ's в публичной части.
 */
class FaqController extends Controller
{
    /**
     * Чтение данных.
     *
     * @param int $school ID школы.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException
     */
    public function read(int $school): JsonResponse
    {
        $action = app(FaqReadAction::class);
        $action->school = $school;

        $data = $action->run();

        $data['success'] = true;

        return response()->json($data);
    }
}
