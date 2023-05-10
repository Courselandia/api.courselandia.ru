<?php
/**
 * Искусственный интеллект писатель.
 * Пакет содержит классы для написания текстов с использованием искусственного интеллекта.
 *
 * @package App.Models.Writer
 */

namespace App\Modules\Writer\Http\Controllers\Admin;

use Log;
use Auth;
use Writer;
use App\Modules\Writer\Http\Requests\Admin\Teacher\WriterWriteRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use App\Models\Exceptions\ResponseException;

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
    public function write(WriterWriteRequest $request): JsonResponse
    {
        try {
            $id = Writer::write($request->get('request'));

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
        }
    }
}
