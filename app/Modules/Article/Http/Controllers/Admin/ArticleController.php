<?php
/**
 * Статьи написанные искусственным интеллектом для разных сущностей.
 * Пакет содержит классы для хранения статей написанных искусственным интеллектом.
 *
 * @package App.Models.Article
 */

namespace App\Modules\Article\Http\Controllers\Admin;

use Auth;
use Log;
use ReflectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use App\Modules\Article\Enums\Status;
use App\Models\Exceptions\ResponseException;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordNotExistException;
use App\Models\Exceptions\ValidateException;
use App\Modules\Article\Actions\Admin\ArticleGetAction;
use App\Modules\Article\Actions\Admin\ArticleReadAction;
use App\Modules\Article\Actions\Admin\ArticleUpdateAction;
use App\Modules\Article\Actions\Admin\ArticleUpdateStatusAction;
use App\Modules\Article\Http\Requests\Admin\ArticleReadRequest;
use App\Modules\Article\Http\Requests\Admin\ArticleUpdateRequest;
use App\Modules\Article\Http\Requests\Admin\ArticleUpdateStatusRequest;
use App\Modules\Article\Http\Requests\Admin\ArticleRewriteRequest;
use App\Modules\Article\Actions\Admin\ArticleRewriteAction;
use App\Modules\Article\Actions\Admin\ArticleApplyAction;
use App\Models\Exceptions\PaymentException;
use App\Models\Exceptions\LimitException;

/**
 * Класс контроллер для работы с категориями в административной части.
 */
class ArticleController extends Controller
{
    /**
     * Получение категории.
     *
     * @param int|string $id ID категории.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException
     */
    public function get(int|string $id): JsonResponse
    {
        $action = new ArticleGetAction($id);
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
     * @param ArticleReadRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException|ReflectionException
     */
    public function read(ArticleReadRequest $request): JsonResponse
    {
        $action = new ArticleReadAction(
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
     * Обновление данных.
     *
     * @param int|string $id ID категории.
     * @param ArticleUpdateRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException
     */
    public function update(int|string $id, ArticleUpdateRequest $request): JsonResponse
    {
        try {
            $action = new ArticleUpdateAction($id, $request->get('text'), $request->get('apply', false));
            $data = $action->run();

            Log::info(
                trans('article::http.controllers.admin.articleController.update.log'),
                [
                    'module' => 'Article',
                    'login' => Auth::getUser()->login,
                    'type' => 'update'
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
     * @param int|string $id ID статьи.
     * @param ArticleUpdateStatusRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException
     */
    public function updateStatus(int|string $id, ArticleUpdateStatusRequest $request): JsonResponse
    {
        try {
            $action = new ArticleUpdateStatusAction($id, Status::from($request->get('status')));
            $data = $action->run();

            Log::info(trans('article::http.controllers.admin.articleController.update.log'), [
                'module' => 'Article',
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
        }
    }

    /**
     * Запрос на переписания текста.
     *
     * @param int|string $id ID статьи.
     * @param ArticleRewriteRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException
     */
    public function rewrite(int|string $id, ArticleRewriteRequest $request): JsonResponse
    {
        try {
            $action = new ArticleRewriteAction($id, $request->get('request'));
            $data = $action->run();

            Log::info(trans('article::http.controllers.admin.articleController.rewrite.log'), [
                'module' => 'Article',
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

    /**
     * Принять и перенести написанный текст в сущность, для которой он был написан.
     *
     * @param int|string $id ID статьи.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ParameterInvalidException
     */
    public function apply(int|string $id): JsonResponse
    {
        try {
            $action = new ArticleApplyAction($id);
            $data = $action->run();

            Log::info(trans('article::http.controllers.admin.articleController.apply.log'), [
                'module' => 'Article',
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
        } catch (ResponseException $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage()
            ])->setStatusCode(503);
        }
    }
}
