<?php
/**
 * Модуль Навыков.
 * Этот модуль содержит все классы для работы с навыками.
 *
 * @package App\Modules\Skill
 */

namespace App\Modules\Skill\Http\Controllers\Site;

use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Skill\Actions\Site\SkillGetAction;
use App\Modules\Skill\Actions\Site\SkillLinkAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

/**
 * Класс контроллер для работы с категориями в публичной части.
 */
class SkillController extends Controller
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
        $action = new SkillGetAction($id);
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
        $action = new SkillLinkAction($link);
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
