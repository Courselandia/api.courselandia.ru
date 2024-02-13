<?php
/**
 * Модуль Инструментов.
 * Этот модуль содержит все классы для работы с инструментами.
 *
 * @package App\Modules\Tool
 */

namespace App\Modules\Tool\Http\Controllers\Site;

use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Tool\Actions\Site\ToolGetAction;
use App\Modules\Tool\Actions\Site\ToolLinkAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

/**
 * Класс контроллер для работы с инструментами в публичной части.
 */
class ToolController extends Controller
{
    /**
     * Получение категории.
     *
     * @param int|string $id ID категории.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function get(int|string $id): JsonResponse
    {
        $action = new ToolGetAction($id);
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
     * Получение категории.
     *
     * @param string $link Ссылка категории.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException
     */
    public function link(string $link): JsonResponse
    {
        $action = new ToolLinkAction($link);
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
