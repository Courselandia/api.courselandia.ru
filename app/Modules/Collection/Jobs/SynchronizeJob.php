<?php
/**
 * Модуль Коллекций.
 * Этот модуль содержит все классы для работы с коллекциями.
 *
 * @package App\Modules\Collection
 */

namespace App\Modules\Collection\Jobs;

use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Collection\Actions\Admin\Collection\CollectionCoursesSyncAction;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Задача для синхронизации курсов коллекции.
 */
class SynchronizeJob
{
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use Dispatchable;

    /**
     * ID коллекции.
     *
     * @var int|string
     */
    private int|string $id;

    /**
     * @param int|string $id ID коллекции.
     */
    public function __construct(int|string $id)
    {
        $this->id = $id;
    }

    /**
     * Выполнение задачи.
     *
     * @return void
     * @throws RecordNotExistException
     */
    public function handle(): void
    {
        $action = new CollectionCoursesSyncAction($this->id);
        $action->run();
    }
}