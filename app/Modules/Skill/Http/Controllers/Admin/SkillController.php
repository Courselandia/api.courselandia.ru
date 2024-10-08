<?php
/**
 * Модуль Навыков.
 * Этот модуль содержит все классы для работы с навыками.
 *
 * @package App\Modules\Skill
 */

namespace App\Modules\Skill\Http\Controllers\Admin;

use App\Models\Exceptions\RecordExistException;
use App\Models\Exceptions\RecordNotExistException;
use App\Models\Exceptions\ValidateException;
use App\Modules\Metatag\Template\TemplateException;
use App\Modules\Skill\Actions\Admin\SkillCreateAction;
use App\Modules\Skill\Actions\Admin\SkillDestroyAction;
use App\Modules\Skill\Actions\Admin\SkillGetAction;
use App\Modules\Skill\Actions\Admin\SkillReadAction;
use App\Modules\Skill\Actions\Admin\SkillUpdateAction;
use App\Modules\Skill\Actions\Admin\SkillUpdateStatusAction;
use App\Modules\Skill\Data\SkillCreate;
use App\Modules\Skill\Data\SkillUpdate;
use App\Modules\Skill\Http\Requests\Admin\SkillCreateRequest;
use App\Modules\Skill\Http\Requests\Admin\SkillDestroyRequest;
use App\Modules\Skill\Http\Requests\Admin\SkillReadRequest;
use App\Modules\Skill\Http\Requests\Admin\SkillUpdateRequest;
use App\Modules\Skill\Http\Requests\Admin\SkillUpdateStatusRequest;
use Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Log;
use ReflectionException;

/**
 * Класс контроллер для работы с навыками в административной части.
 */
class SkillController extends Controller
{
    /**
     * Получение навыка.
     *
     * @param int|string $id ID навыка.
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
     * Чтение данных.
     *
     * @param SkillReadRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ReflectionException
     */
    public function read(SkillReadRequest $request): JsonResponse
    {
        $action = new SkillReadAction(
            $request->get('sorts'),
            $request->get('filters'),
            $request->get('offset'),
            $request->get('limit'),
        );

        $data = $action->run();
        $data['success'] = true;

        return response()->json($data);
    }

    /**
     * Добавление данных.
     *
     * @param SkillCreateRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function create(SkillCreateRequest $request): JsonResponse
    {
        try {
            $data = SkillCreate::from($request->toArray());
            $action = new SkillCreateAction($data);
            $data = $action->run();

            Log::info(
                trans('skill::http.controllers.admin.skillController.create.log'),
                [
                    'module' => 'Skill',
                    'login' => Auth::getUser()->login,
                    'type' => 'create'
                ]
            );

            $data = [
                'data' => $data,
                'success' => true
            ];

            return response()->json($data);
        } catch (ValidateException|TemplateException $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage()
            ])->setStatusCode(400);
        } catch (RecordExistException $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage()
            ])->setStatusCode(404);
        }
    }

    /**
     * Обновление данных.
     *
     * @param int|string $id ID навыка.
     * @param SkillUpdateRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function update(int|string $id, SkillUpdateRequest $request): JsonResponse
    {
        try {
            $data = SkillUpdate::from([
                ...$request->toArray(),
                'id' => $id,
            ]);
            $action = new SkillUpdateAction($data);
            $data = $action->run();

            Log::info(
                trans('skill::http.controllers.admin.skillController.update.log'),
                [
                    'module' => 'Skill',
                    'login' => Auth::getUser()->login,
                    'type' => 'update'
                ]
            );

            $data = [
                'data' => $data,
                'success' => true
            ];

            return response()->json($data);
        } catch (ValidateException|RecordExistException|TemplateException $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage()
            ])->setStatusCode(400);
        } catch (RecordNotExistException $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage()
            ])->setStatusCode(404);
        }
    }

    /**
     * Обновление статуса.
     *
     * @param int|string $id ID пользователя.
     * @param SkillUpdateStatusRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function updateStatus(int|string $id, SkillUpdateStatusRequest $request): JsonResponse
    {
        try {
            $action = new SkillUpdateStatusAction($id, $request->get('status'));
            $data = $action->run();

            Log::info(trans('skill::http.controllers.admin.skillController.update.log'), [
                'module' => 'User',
                'login' => Auth::getUser()->login,
                'type' => 'update'
            ]);

            $data = [
                'success' => true,
                'data' => $data
            ];

            return response()->json($data);
        } catch (ValidateException|RecordExistException $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage()
            ])->setStatusCode(400);
        } catch (RecordNotExistException $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage()
            ])->setStatusCode(404);
        }
    }

    /**
     * Удаление данных.
     *
     * @param SkillDestroyRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function destroy(SkillDestroyRequest $request): JsonResponse
    {
        $action = new SkillDestroyAction($request->get('ids'));
        $action->run();

        Log::info(
            trans('skill::http.controllers.admin.skillController.destroy.log'),
            [
                'module' => 'Skill',
                'login' => Auth::getUser()->login,
                'type' => 'destroy'
            ]
        );

        $data = [
            'success' => true
        ];

        return response()->json($data);
    }
}
