<?php
/**
 * Модуль ядра системы.
 * Этот модуль содержит все классы для работы с ядром системы.
 *
 * @package App\Modules\Core
 */

namespace App\Modules\Core\Actions\Admin;

use Log;
use Cache;
use Artisan;
use Config;
use App\Models\Action;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Класс для сброса кеша в административной системе и в публичной части.
 */
class CacheFlushAction extends Action
{
    /**
     * Метод запуска логики.
     *
     * @return bool Вернет результаты исполнения.
     * @throws GuzzleException
     */
    public function run(): bool
    {
        Cache::flush();

        Artisan::call('view:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');

        Artisan::call('config:cache');
        Artisan::call('route:cache');

        $client = new Client();

        try {
            $client->request('GET', Config::get('app.url') . '/' . 'cache-flush');
        } catch (Exception $error) {
            Log::debug('Не удалось удалить кешь в публичной части: ' . $error->getMessage());
        }

        return true;
    }
}
