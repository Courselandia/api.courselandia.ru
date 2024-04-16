<?php
/**
 * Модуль Разделов.
 * Этот модуль содержит все классы для работы с разделами каталога.
 *
 * @package App\Modules\Section
 */

namespace App\Modules\Section\Http\Controllers\Admin;

use Auth;
use Log;
use ReflectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use App\Modules\Salary\Enums\Level;
use App\Models\Exceptions\RecordExistException;
use App\Models\Exceptions\RecordNotExistException;
use App\Models\Exceptions\ValidateException;
use App\Modules\Metatag\Template\TemplateException;
use App\Modules\Section\Actions\Admin\SectionCreateAction;
use App\Modules\Section\Actions\Admin\SectionDestroyAction;
use App\Modules\Section\Actions\Admin\SectionGetAction;
use App\Modules\Section\Actions\Admin\SectionReadAction;
use App\Modules\Section\Actions\Admin\SectionUpdateAction;
use App\Modules\Section\Actions\Admin\SectionUpdateStatusAction;
use App\Modules\Section\Data\SectionCreate;
use App\Modules\Section\Data\SectionUpdate;
use App\Modules\Section\Http\Requests\Admin\SectionCreateRequest;
use App\Modules\Section\Http\Requests\Admin\SectionDestroyRequest;
use App\Modules\Section\Http\Requests\Admin\SectionReadRequest;
use App\Modules\Section\Http\Requests\Admin\SectionUpdateRequest;
use App\Modules\Section\Http\Requests\Admin\SectionUpdateStatusRequest;

/**
 * Класс контроллер для работы с разделами в административной части.
 */
class SectionController extends Controller
{
    /**
     * Получение раздела.
     *
     * @param int|string $id ID раздела.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function get(int|string $id): JsonResponse
    {
        $action = new SectionGetAction($id);
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
     * @param SectionReadRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ReflectionException
     */
    public function read(SectionReadRequest $request): JsonResponse
    {
        $action = new SectionReadAction(
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
     * @param SectionCreateRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function create(SectionCreateRequest $request): JsonResponse
    {
        try {
            $data = SectionCreate::from([
                ...$request->toArray(),
                'level' => $request->get('level') ? Level::from($request->get('level')) : null,
                'items' => $request->get('items')
            ]);
            $action = new SectionCreateAction($data);
            $data = $action->run();

            Log::info(
                trans('section::http.controllers.admin.sectionController.create.log'),
                [
                    'module' => 'Section',
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
     * @param int|string $id ID раздела.
     * @param SectionUpdateRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function update(int|string $id, SectionUpdateRequest $request): JsonResponse
    {
        try {
            $data = SectionUpdate::from([
                ...$request->toArray(),
                'id' => $id,
                'level' => $request->get('level') ? Level::from($request->get('level')) : null,
                'items' => $request->get('items')
            ]);
            $action = new SectionUpdateAction($data);
            $data = $action->run();

            Log::info(
                trans('section::http.controllers.admin.sectionController.update.log'),
                [
                    'module' => 'Section',
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
     * @param int|string $id ID раздела.
     * @param SectionUpdateStatusRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function updateStatus(int|string $id, SectionUpdateStatusRequest $request): JsonResponse
    {
        try {
            $action = new SectionUpdateStatusAction($id, $request->get('status'));
            $data = $action->run();

            Log::info(trans('section::http.controllers.admin.sectionController.update.log'), [
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
     * @param SectionDestroyRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function destroy(SectionDestroyRequest $request): JsonResponse
    {
        $action = new SectionDestroyAction($request->get('ids'));
        $action->run();

        Log::info(
            trans('section::http.controllers.admin.sectionController.destroy.log'),
            [
                'module' => 'Section',
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
