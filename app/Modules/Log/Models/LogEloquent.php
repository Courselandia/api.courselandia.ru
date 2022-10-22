<?php
/**
 * Модуль Логирование.
 * Этот модуль содержит все классы для работы с логированием.
 *
 * @package App\Modules\Log
 */

namespace App\Modules\Log\Models;

use App\Models\Sortable;
use App\Modules\Log\Filters\LogFilter;
use Eloquent;
use App\Models\Delete;
use danielme85\LaravelLogToDB\Models\LogToDbCreateObject;
use EloquentFilter\Filterable;

/**
 * Класс модель для таблицы логов на основе Eloquent.
 *
 * @property int|string $id ID.
 * @property ?string $message Сообщение.
 * @property ?string $channel Канал.
 * @property string $level Уровень.
 * @property string $level_name Название уровня лога.
 * @property int $unix_time Дата в Unix формате.
 * @property ?string $datetime Дата записи.
 * @property ?string $context Контекст.
 * @property ?string $extra Дополнительные данные.
 */
class LogEloquent extends Eloquent
{
    use Delete;
    use Sortable;
    use LogToDbCreateObject;
    use Filterable;

    /**
     * Название таблицы базы данных.
     *
     * @var string
     */
    protected $table = 'logs';

    /**
     * Класс фильтр.
     *
     * @return string Название класса фильтра.
     */
    public function modelFilter(): string
    {
        return $this->provideFilter(LogFilter::class);
    }
}
