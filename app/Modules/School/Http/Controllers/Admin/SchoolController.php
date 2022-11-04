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
use App\Modules\School\Actions\Admin\School\SchoolCreateAction;
use App\Modules\School\Actions\Admin\School\SchoolDestroyAction;
use App\Modules\School\Actions\Admin\School\SchoolGetAction;
use App\Modules\School\Actions\Admin\School\SchoolReadAction;
use App\Modules\School\Actions\Admin\School\SchoolUpdateAction;
use App\Modules\School\Actions\Admin\School\SchoolUpdateStatusAction;
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
     * @throws ParameterInvalidException|ReflectionException
     */
    public function get(int|string $id): JsonResponse
    {
        $action = app(SchoolGetAction::class);
        $action->id = $id;
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
        $action = app(SchoolReadAction::class);
        $action->sorts = $request->get('sorts');
        $action->filters = $request->get('filters');
        $action->offset = $request->get('offset');
        $action->limit = $request->get('limit');

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
     * @throws RecordNotExistException|ParameterInvalidException|ReflectionException
     */
    public function create(SchoolCreateRequest $request): JsonResponse
    {
        try {
            $action = app(SchoolCreateAction::class);
            $action->name = $request->get('name');
            $action->header = $request->get('header');
            $action->link = $request->get('link');
            $action->text = $request->get('text');
            $action->rating = $request->get('rating', 0);
            $action->site = $request->get('site');
            $action->status = $request->get('status');
            $action->title = $request->get('title');
            $action->description = $request->get('description');
            $action->keywords = $request->get('keywords');

            if ($request->hasFile('imageLogo') && $request->file('imageLogo')->isValid()) {
                $action->image_logo_id = $request->file('imageLogo');
            }

            if ($request->hasFile('imageSite') && $request->file('imageSite')->isValid()) {
                $action->image_site_id = $request->file('imageSite');
            }

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
        } catch (ValidateException $error) {
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
     * @throws ParameterInvalidException|ReflectionException
     */
    public function update(int|string $id, SchoolUpdateRequest $request): JsonResponse
    {
        try {
            $action = app(SchoolUpdateAction::class);
            $action->id = $id;
            $action->name = $request->get('name');
            $action->header = $request->get('header');
            $action->link = $request->get('link');
            $action->text = $request->get('text');
            $action->rating = $request->get('rating', 0);
            $action->site = $request->get('site');
            $action->status = $request->get('status');
            $action->title = $request->get('title');
            $action->description = $request->get('description');
            $action->keywords = $request->get('keywords');

            if ($request->hasFile('imageLogo') && $request->file('imageLogo')->isValid()) {
                $action->image_logo_id = $request->file('imageLogo');
            }

            if ($request->hasFile('imageSite') && $request->file('imageSite')->isValid()) {
                $action->image_site_id = $request->file('imageSite');
            }

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
     * Обновление статуса.
     *
     * @param int|string $id ID пользователя.
     * @param SchoolUpdateStatusRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException|ReflectionException
     */
    public function updateStatus(int|string $id, SchoolUpdateStatusRequest $request): JsonResponse
    {
        try {
            $action = app(SchoolUpdateStatusAction::class);
            $action->id = $id;
            $action->status = $request->get('status');

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
     * @throws ParameterInvalidException
     */
    public function destroy(SchoolDestroyRequest $request): JsonResponse
    {
        $action = app(SchoolDestroyAction::class);
        $action->ids = $request->get('ids');
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
