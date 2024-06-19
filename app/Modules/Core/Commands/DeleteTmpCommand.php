<?php
/**
 * Модуль ядра системы.
 * Этот модуль содержит все классы для работы с ядром системы.
 *
 * @package App\Modules\Core
 */

namespace App\Modules\Core\Commands;

use App\Modules\Core\Actions\Admin\DeleteTmpAction;
use Illuminate\Console\Command;

/**
 * Удаление временных файлов.
 */
class DeleteTmpCommand extends Command
{
    /**
     * Название консольной команды.
     *
     * @var string
     */
    protected $signature = 'tmp:delete';

    /**
     * Описание консольной команды.
     *
     * @var string
     */
    protected $description = 'Удаление временных файлов.';

    /**
     * Выполнение команды.
     *
     * @return void
     */
    public function handle(): void
    {
        $action = new DeleteTmpAction();
        $action->run();
    }
}
