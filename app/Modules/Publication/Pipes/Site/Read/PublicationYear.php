<?php
/**
 * Модуль Публикации.
 * Этот модуль содержит все классы для работы с публикациями.
 *
 * @package App\Modules\Publication
 */

namespace App\Modules\Publication\Pipes\Site\Read;

use App\Models\Contracts\Pipe;
use App\Models\Data;
use App\Models\Enums\CacheTime;
use App\Modules\Publication\Data\Decorators\PublicationRead as PublicationReadData;
use App\Modules\Publication\Models\Publication;
use App\Modules\Publication\Values\PublicationYear as PublicationYearValue;
use Cache;
use Carbon\Carbon;
use Closure;
use Util;

/**
 * Класс пайплайн для разбивки публикаций по годам.
 */
class PublicationYear implements Pipe
{
    /**
     * Метод, который будет вызван у pipeline.
     *
     * @param Data|PublicationReadData $data $entity Данные для чтения публикаций
     * @param Closure $next Ссылка на следующий pipe.
     *
     * @return mixed Вернет значение полученное после выполнения следующего pipe.
     */
    public function handle(Data|PublicationReadData $data, Closure $next): mixed
    {
        if ($data->limit) {
            $cacheKey = Util::getKey('publication', 'model');

            $publications = Cache::tags(['publication'])->remember(
                $cacheKey,
                CacheTime::GENERAL->value,
                function () {
                    return Publication::active()
                        ->select('published_at')
                        ->orderBy('published_at', 'desc')
                        ->orderBy('id', 'desc')
                        ->get();
                }
            );

            $years = [];

            for ($i = 0; $i < count($publications); $i++) {
                $years[] = Carbon::parse($publications[$i]->published_at)->year;
            }

            $years = array_unique($years);
            $items = [];
            $year = $data->year ?? Carbon::now()->year;

            foreach ($years as $yr) {
                $items[] = new PublicationYearValue($yr, $yr === $year);
            }

            $data->years = $items;
        }

        return $next($data);
    }
}
