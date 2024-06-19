<?php
/**
 * Модуль ядра системы.
 * Этот модуль содержит все классы для работы с ядром системы.
 *
 * @package App\Modules\Core
 */

namespace App\Modules\Core\Actions\Admin;

use Storage;
use App\Models\Action;

/**
 * Класс для удаления временных файлов.
 */
class DeleteTmpAction extends Action
{
    /**
     * Метод запуска логики.
     *
     * @return bool Вернет результаты исполнения.
     */
    public function run(): bool
    {
        $files = Storage::drive('local')->allFiles('/tmp');

        foreach ($files as $file) {
            Storage::drive('local')->delete($file);
        }

        return true;
    }
}
