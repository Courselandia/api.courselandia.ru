<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\DbFile\Jobs;

use Storage;
use App\Modules\Course\DbFile\Item;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

/**
 * Задача для формирования.
 */
abstract class JobItem implements ShouldQueue
{
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use Dispatchable;

    /**
     * ID Записи.
     *
     * @var int|null
     */
    public ?int $id = null;

    /**
     * Путь к папке для хранения файла с данными.
     *
     * @var string
     */
    public string $path;

    /**
     * Ссылка на секцию.
     *
     * @var string|null
     */
    public ?string $link = null;

    /**
     * Конструктор.
     *
     * @param int|null $id ID записи.
     */
    public function __construct(string $path, ?int $id = null, ?string $link = null)
    {
        $this->path = $path;
        $this->id = $id;
        $this->link = $link;
    }

    /**
     * Сохранение данных в файл.
     *
     * @param Item $item Данные.
     *
     * @return void
     */
    protected function save(Item $item): void
    {
        $name = $this->id ?: 'default';
        $path = '/db/' . $this->path . '/' . $name. '.obj';

        Storage::drive('local')->put($path, serialize($item->data));
    }
}
