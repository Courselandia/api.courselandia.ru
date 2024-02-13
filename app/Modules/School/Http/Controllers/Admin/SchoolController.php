<?php
/**
 * Модуль Школ.
 * Этот модуль содержит все классы для работы со школами.
 *
 * @package App\Modules\School
 */

namespace App\Modules\School\Http\Controllers\Admin;

use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordExistException;
use App\Models\Exceptions\RecordNotExistException;
use App\Models\Exceptions\ValidateException;
use App\Modules\Metatag\Template\TemplateException;
use App\Modules\School\Actions\Admin\School\SchoolCreateAction;
use App\Modules\School\Actions\Admin\School\SchoolDestroyAction;
use App\Modules\School\Actions\Admin\School\SchoolGetAction;
use App\Modules\School\Actions\Admin\School\SchoolReadAction;
use App\Modules\School\Actions\Admin\School\SchoolUpdateAction;
use App\Modules\School\Actions\Admin\School\SchoolUpdateStatusAction;
use App\Modules\School\Data\SchoolCreate;
use App\Modules\School\Data\SchoolUpdate;
use App\Modules\School\Http\Requests\Admin\School\SchoolCreateRequest;
use App\Modules\School\Http\Requests\Admin\School\SchoolDestroyRequest;
use App\Modules\School\Http\Requests\Admin\School\SchoolReadRequest;
use App\Modules\School\Http\Requests\Admin\School\SchoolUpdateRequest;
use App\Modules\School\Http\Requests\Admin\School\SchoolUpdateStatusRequest;
use Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Log;
use ReflectionException;

/**
 * Класс контроллер для работы со школами в административной части.
 */
class SchoolController extends Controller
{
    /**
     * Получение школы.
     *
     * @param int|string $id ID школы.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function get(int|string $id): JsonResponse
    {
        $action = new SchoolGetAction($id);
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
     * @param SchoolReadRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException|ReflectionException
     */
    public function read(SchoolReadRequest $request): JsonResponse
    {
        $action = new SchoolReadAction(
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
     * @param SchoolCreateRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException
     */
    public function create(SchoolCreateRequest $request): JsonResponse
    {
        try {
            $data = SchoolCreate::from([
                ...$request->all(),
                'rating' => $request->get('rating', 0),
            ]);

            if ($request->hasFile('imageLogo') && $request->file('imageLogo')->isValid()) {
                $data->image_logo_id = $request->file('imageLogo');
            }

            if ($request->hasFile('imageSite') && $request->file('imageSite')->isValid()) {
                $data->image_site_id = $request->file('imageSite');
            }

            $action = new SchoolCreateAction($data);
            $data = $action->run();

            Log::info(
                trans('school::http.controllers.admin.schoolController.create.log'),
                [
                    'module' => 'School',
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
     * @param int|string $id ID школы.
     * @param SchoolUpdateRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException
     */
    public function update(int|string $id, SchoolUpdateRequest $request): JsonResponse
    {
        try {
            $data = SchoolUpdate::from([
                ...$request->all(),
                'id' => $id,
                'rating' => $request->get('rating', 0),
            ]);

            if ($request->hasFile('imageLogo') && $request->file('imageLogo')->isValid()) {
                $data->image_logo_id = $request->file('imageLogo');
            }

            if ($request->hasFile('imageSite') && $request->file('imageSite')->isValid()) {
                $data->image_site_id = $request->file('imageSite');
            }

            $action = new SchoolUpdateAction($data);
            $data = $action->run();

            Log::info(
                trans('school::http.controllers.admin.schoolController.update.log'),
                [
                    'module' => 'School',
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
     * @param SchoolUpdateStatusRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function updateStatus(int|string $id, SchoolUpdateStatusRequest $request): JsonResponse
    {
        try {
            $action = new SchoolUpdateStatusAction($id, $request->get('status'));
            $data = $action->run();

            Log::info(trans('school::http.controllers.admin.schoolController.update.log'), [
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
     * @param SchoolDestroyRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function destroy(SchoolDestroyRequest $request): JsonResponse
    {
        $action = new SchoolDestroyAction($request->get('ids'));
        $action->run();

        Log::info(
            trans('school::http.controllers.admin.schoolController.destroy.log'),
            [
                'module' => 'School',
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
