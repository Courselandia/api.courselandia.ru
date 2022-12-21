<?php
/**
 * Модуль ядра системы.
 * Этот модуль содержит все классы для работы с ядром системы.
 *
 * @package App\Modules\Core
 */

namespace App\Modules\Core\Http\Controllers\Site;

use App\Modules\Core\Actions\Site\TestMailSendAction;
use App\Modules\Core\Http\Requests\Site\CoreMailRequest;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

/**
 * Класс контроллер для работы с ядром дополнительных возможностей.
 */
class CoreController extends Controller
{
    /**
     * Удаление кеша.
     *
     * @return Response Вернет ответ.
     */
    public function mail(CoreMailRequest $request): Response
    {
        $action = app(TestMailSendAction::class);
        $action->email = $request->get('email');

        $action->run();

        return response('OK');
    }
}
