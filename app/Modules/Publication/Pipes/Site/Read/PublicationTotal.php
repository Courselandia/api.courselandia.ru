<?php
/**
 * Модуль Публикации.
 * Этот модуль содержит все классы для работы с публикациями.
 *
 * @package App\Modules\Publication
 */

namespace App\Modules\Publication\Pipes\Site\Read;

use App\Models\Data;
use App\Models\Enums\CacheTime;
use App\Modules\Publication\Data\Decorators\PublicationRead as PublicationReadData;
use Cache;
use Closure;
use App\Models\Contracts\Pipe;
use App\Modules\Publication\Models\Publication;
use Util;

/**
 * Декоратор пайплан пагинации для публикаций.
 */
class PublicationTotal implements Pipe
{
    /**
     * Метод, который будет вызван у pipeline.
     *
     * @param Data|PublicationReadData $data $entity Данные для чтения публикаций.
     * @param Closure $next Ссылка на следующий pipe.
     *
     * @return mixed Вернет значение полученное после выполнения следующего pipe.
     */
    public function handle(Data|PublicationReadData $data, Closure $next): mixed
    {
        if ($data->limit) {
            $year = $data->year;
            $cacheKey = Util::getKey('publication', 'count', $year, 'active');

            $data->total = Cache::tags(['publication'])->remember(
                $cacheKey,
                CacheTime::GENERAL->value,
                function () use ($year) {
                    return Publication::year($year)->active()->count();
                }
            );
        }

        return $next($data);
    }
}
