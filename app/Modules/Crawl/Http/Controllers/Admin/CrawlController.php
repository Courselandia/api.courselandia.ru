<?php
/**
 * Модуль индексации страниц.
 * Этот модуль содержит все классы для системы индексации страниц поисковыми системами.
 *
 * @package App\Modules\Crawl
 */

namespace App\Modules\Crawl\Http\Controllers\Admin;

use App\Modules\Crawl\Actions\Admin\CrawlPlanAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use ReflectionException;
use App\Modules\Crawl\Actions\Admin\CrawlReadAction;
use App\Modules\Crawl\Http\Requests\Admin\CrawlReadRequest;

/**
 * Класс контроллер для работы с индексированием в административной части.
 */
class CrawlController extends Controller
{
    /**
     * Чтение данных.
     *
     * @param CrawlReadRequest $request Запрос.
     *
     * @return JsonResponse Вернет JSON ответ.
     * @throws ReflectionException
     */
    public function read(CrawlReadRequest $request): JsonResponse
    {
        $action = new CrawlReadAction(
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
     * Запуск планирования задач.
     *
     * @return JsonResponse
     */
    public function plan(): JsonResponse
    {
        $action = new CrawlPlanAction();
        $action->run();

        return response()->json([
            'success' => true,
        ]);
    }
}
