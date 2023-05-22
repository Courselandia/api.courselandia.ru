<?php
/**
 * Искусственный интеллект писатель.
 * Пакет содержит классы для написания текстов с использованием искусственного интеллекта.
 *
 * @package App.Models.Writer
 */

namespace App\Modules\Writer\Http\Controllers\Admin;

use App\Models\Exceptions\PaymentException;
use App\Models\Exceptions\ProcessingException;
use Log;
use Auth;
use Writer;
use App\Modules\Writer\Http\Requests\Admin\Teacher\WriterWriteRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use App\Models\Exceptions\ResponseException;
use App\Models\Exceptions\RecordNotExistException;

/**
 * Класс контроллер для написания текстов
 */
class WriterController extends Controller
{
    /**
     * Написание текста.
     *
     * @param WriterWriteRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function request(WriterWriteRequest $request): JsonResponse
    {
        try {
            $id = Writer::request($request->get('request'));

            Log::info(
                trans('writer::http.controllers.admin.writerController.write.log'),
                [
                    'module' => 'Writer',
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
        } catch (ResponseException $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage(),
            ])->setStatusCode(503);
        }
    }

    /**
     * Получение результата написания текста.
     *
     * @param string $id ID задания на написания.
     *
     * @return JsonResponse Вернет JSON ответ.
     */
    public function result(string $id): JsonResponse
    {
        try {
            $text = Writer::result($id);

            $data = [
                'data' => [
                    'text' => $text,
                ],
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
