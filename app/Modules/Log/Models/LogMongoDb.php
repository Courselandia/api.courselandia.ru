<?php
/**
 * Модуль Логирование.
 * Этот модуль содержит все классы для работы с логированием.
 *
 * @package App\Modules\Log
 */

namespace App\Modules\Log\Models;

use MongoDb;
use App\Models\Delete;
use App\Models\Sortable;
use App\Modules\Log\Filters\LogFilter;
use EloquentFilter\Filterable;
use danielme85\LaravelLogToDB\Models\LogToDbCreateObject;

/**
 * Класс модель для таблицы логов на основе MongoDb.
 *
 * @property int|string $id ID.
 * @property ?string $message Сообщение.
 * @property ?string $channel Канал.
 * @property string $level Уровень.
 * @property string $level_name Название уровня лога.
 * @property int $unix_time Дата в Unix формате.
 * @property ?string $datetime Время записи.
 * @property ?string $context Контекст.
 * @property ?string $extra Дополнительные данные.
 */
class LogMongoDb extends MongoDb
{
    use Delete;
    use Sortable;
    use LogToDbCreateObject;
    use Filterable;

    /**
     * Связанная с моделью таблица.
     *
     * @var string
     */
    protected $collection = 'logs';

    /**
     * Тип соединения.
     *
     * @var string
     */
    protected $connection = 'mongodb';

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
