<?php
/**
 * Система проверки плагиата.
 * Пакет содержит классы для проведения анализа на наличие плагиата.
 *
 * @package App.Models.Plagiarism
 */

namespace App\Modules\Plagiarism\Http\Controllers\Admin;

use App\Modules\Plagiarism\Exceptions\TextShortException;
use Log;
use Auth;
use Plagiarism;
use App\Models\Exceptions\LimitException;
use App\Models\Exceptions\PaymentException;
use App\Models\Exceptions\ProcessingException;
use App\Models\Exceptions\RecordNotExistException;
use App\Models\Exceptions\ResponseException;
use App\Modules\Plagiarism\Http\Requests\Admin\PlagiarismAnalyzeRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

/**
 * Класс контроллер для анализа текста.
 */
class PlagiarismController extends Controller
{
    /**
     * Анализ текста.
     *
     * @param PlagiarismAnalyzeRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function request(PlagiarismAnalyzeRequest $request): JsonResponse
    {
        try {
            $id = Plagiarism::request($request->get('text'));

            Log::info(
                trans('plagiarism::http.controllers.admin.plagiarismController.request.log'),
                [
                    'module' => 'Plagiarism',
                    'login' => Auth::getUser()->login,
                    'type' => 'create'
                ]
            );

            $data = [
                'data' => [
                    'id' => $id,
                ],
                'success' => true,
            ];

            return response()->json($data);
        } catch (PaymentException $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage(),
            ])->setStatusCode(402);
        } catch (LimitException|TextShortException $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage(),
            ])->setStatusCode(400);
        } catch (ResponseException $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage(),
            ])->setStatusCode(503);
        }
    }

    /**
     * Получение результата анализа текста.
     *
     * @param string $id ID задания на анализ.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function result(string $id): JsonResponse
    {
        try {
            $result = Plagiarism::result($id);

            $data = [
                'data' => $result,
                'success' => true,
            ];

            return response()->json($data);
        } catch (ResponseException $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage(),
            ])->setStatusCode(503);
        } catch (RecordNotExistException $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage(),
            ])->setStatusCode(404);
        } catch (ProcessingException $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage(),
            ])->setStatusCode(501);
        } catch (PaymentException $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage(),
            ])->setStatusCode(402);
        }
    }
}
