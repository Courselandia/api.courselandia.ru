<?php
/**
 * Модуль индексации страниц.
 * Этот модуль содержит все классы для системы индексации страниц поисковыми системами.
 *
 * @package App\Modules\Crawl
 */

namespace App\Modules\Crawl\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use ReflectionException;
use App\Models\Exceptions\ParameterInvalidException;
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
     * @throws ParameterInvalidException|ReflectionException
     */
    public function read(CrawlReadRequest $request): JsonResponse
    {
        $action = app(CrawlReadAction::class);
        $action->sorts = $request->get('sorts');
        $action->filters = $request->get('filters');
        $action->offset = $request->get('offset');
        $action->limit = $request->get('limit');

        $data = $action->run();

        $data['success'] = true;

        return response()->json($data);
    }
}
