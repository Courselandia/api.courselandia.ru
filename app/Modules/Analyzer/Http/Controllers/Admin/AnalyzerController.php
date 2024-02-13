<?php
/**
 * Статьи написанные искусственным интеллектом для разных сущностей.
 * Пакет содержит классы для хранения статей написанных искусственным интеллектом.
 *
 * @package App.Models.Analyzer
 */

namespace App\Modules\Analyzer\Http\Controllers\Admin;

use Auth;
use Log;
use ReflectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use App\Models\Exceptions\ResponseException;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordNotExistException;
use App\Models\Exceptions\ValidateException;
use App\Modules\Analyzer\Actions\Admin\AnalyzerGetAction;
use App\Modules\Analyzer\Actions\Admin\AnalyzerReadAction;
use App\Modules\Analyzer\Actions\Admin\AnalyzerAnalyzeAction;
use App\Modules\Analyzer\Http\Requests\Admin\AnalyzerReadRequest;
use App\Models\Exceptions\PaymentException;
use App\Models\Exceptions\LimitException;

/**
 * Класс контроллер для работы анализируемыми текстами в административной системе.
 */
class AnalyzerController extends Controller
{
    /**
     * Получение анализируемых данных.
     *
     * @param int|string $id ID данных.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException
     */
    public function get(int|string $id): JsonResponse
    {
        $action = new AnalyzerGetAction($id);
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
     * @param AnalyzerReadRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException|ReflectionException
     */
    public function read(AnalyzerReadRequest $request): JsonResponse
    {
        $action = new AnalyzerReadAction(
            $request->get('sorts'),
            $request->get('filters'),
            $request->get('offset'),
            $request->get('limit')
        );

        $data = $action->run();

        $data['success'] = true;

        return response()->json($data);
    }

    /**
     * Запрос на повторный анализ.
     *
     * @param int|string $id ID данных.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException
     */
    public function analyze(int|string $id): JsonResponse
    {
        try {
            $action = new AnalyzerAnalyzeAction($id);
            $data = $action->run();

            Log::info(trans('analyzer::http.controllers.admin.analyzerController.analyze.log'), [
                'module' => 'Analyzer',
                'login' => Auth::getUser()->login,
                'type' => 'update'
            ]);

            $data = [
                'success' => true,
                'data' => $data
            ];

            return response()->json($data);
        } catch (ValidateException $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage()
            ])->setStatusCode(400);
        } catch (RecordNotExistException $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage()
            ])->setStatusCode(404);
        } catch (PaymentException $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage()
            ])->setStatusCode(402);
        } catch (LimitException $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage()
            ])->setStatusCode(400);
        } catch (ResponseException $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage()
            ])->setStatusCode(503);
        }
    }
}
