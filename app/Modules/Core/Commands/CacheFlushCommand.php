<?php
/**
 * Модуль ядра системы.
 * Этот модуль содержит все классы для работы с ядром системы.
 *
 * @package App\Modules\Core
 */

namespace App\Modules\Core\Commands;

use App\Modules\Core\Actions\Admin\CacheFlushAction;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;

/**
 * Полное стирание кеша.
 */
class CacheFlushCommand extends Command
{
    /**
     * Название консольной команды.
     *
     * @var string
     */
    protected $signature = 'cache:flush';

    /**
     * Описание консольной команды.
     *
     * @var string
     */
    protected $description = 'Полное стирание кеша.';

    /**
     * Выполнение команды.
     *
     * @return void
     * @throws GuzzleException
     */
    public function handle(): void
    {
        app(CacheFlushAction::class)->run();
    }
}
