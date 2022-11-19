<?php
/**
 * Модуль предупреждений.
 * Этот модуль содержит все классы для работы с предупреждениями.
 *
 * @package App\Modules\Alert
 */

namespace App\Modules\Alert\Http\Controllers\Admin;

use Alert;
use Log;
use Auth;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Alert\Actions\Admin\AlertReadAction;
use Illuminate\Routing\Controller;
use Illuminate\Http\JsonResponse;
use App\Modules\Alert\Http\Requests\Admin\AlertReadRequest;
use App\Modules\Alert\Http\Requests\Admin\AlertDestroyRequest;
use App\Modules\Alert\Http\Requests\Admin\AlertStatusRequest;

/**
 * Класс контроллер для работы с предупреждениями в административной системе.
 */
class AlertController extends Controller
{
    /**
     * Чтение данных.
     *
     * @param  AlertReadRequest  $request  Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function read(AlertReadRequest $request): JsonResponse
    {
        $action = app(AlertReadAction::class);
        $action->offset = $request->get('offset');
        $action->limit = $request->get('limit');
        $action->status = $request->get('status');

        $data = $action->run();

        $data['success'] = true;

        return response()->json($data);
    }

    /**
     * Установка предупреждения как прочитанное.
     *
     * @param  int|string  $id  ID предупреждения.
     * @param  AlertStatusRequest  $request  Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function status(int|string $id, AlertStatusRequest $request): JsonResponse
    {
        try {
            Alert::setStatus($id, $request->get('status'));

            Log::info(trans('alert::http.controllers.site.alertController.status.log'), [
                'module' => 'Alert',
                'login' => Auth::getUser()->login,
                'type' => 'update'
            ]);

            $data = [
                'success' => true,
                'data' => Alert::get($id)
            ];

            return response()->json($data);
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
     * @param  AlertDestroyRequest  $request  Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function destroy(AlertDestroyRequest $request): JsonResponse
    {
        Alert::remove($request->get('ids'));

        Log::info(trans('alert::http.controllers.site.alertController.destroy.log'), [
            'module' => 'Alert',
            'login' => Auth::getUser()->login,
            'type' => 'destroy'
        ]);

        $data = [
            'success' => true,
        ];

        return response()->json($data);
    }
}
