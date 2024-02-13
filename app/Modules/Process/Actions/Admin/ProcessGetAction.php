<?php
/**
 * Модуль Как проходит обучение.
 * Этот модуль содержит все классы для работы с объяснением как проходит обучение.
 *
 * @package App\Modules\Process
 */

namespace App\Modules\Process\Actions\Admin;

use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Modules\Process\Entities\Process as ProcessEntity;
use App\Modules\Process\Models\Process;
use Cache;
use Util;

/**
 * Класс действия для получения объяснения как проходит обучение.
 */
class ProcessGetAction extends Action
{
    /**
     * ID объяснения как проходит обучение.
     *
     * @var int|string
     */
    private int|string $id;

    /**
     * @param int|string $id ID объяснения как проходит обучение.
     */
    public function __construct(int|string $id)
    {
        $this->id = $id;
    }

    /**
     * Метод запуска логики.
     *
     * @return ProcessEntity|null Вернет результаты исполнения.
     */
    public function run(): ?ProcessEntity
    {
        $cacheKey = Util::getKey('process', $this->id, 'metatag');

        return Cache::tags(['catalog', 'process'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $process = Process::where('id', $this->id)
                    ->first();

                return $process ? ProcessEntity::from($process->toArray()) : null;
            }
        );
    }
}
