<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Json\Jobs;

use Storage;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

/**
 * Задача для формирования JSON данных.
 */
abstract class JsonItemJob implements ShouldQueue
{
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use Dispatchable;

    /**
     * Ссылка на файл для сохранения.
     *
     * @var string
     */
    protected string $path;

    /**
     * Сохранение данных в файл.
     *
     * @param array $data Данные.
     *
     * @return void
     */
    protected function save(array $data): void
    {
        Storage::drive('public')->put($this->path, json_encode($data));
    }
}
