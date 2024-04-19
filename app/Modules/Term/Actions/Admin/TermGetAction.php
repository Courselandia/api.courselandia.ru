<?php
/**
 * Модуль Термином.
 * Этот модуль содержит все классы для работы с терминами.
 *
 * @package App\Modules\Term
 */

namespace App\Modules\Term\Actions\Admin;

use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Modules\Term\Entities\Term as TermEntity;
use App\Modules\Term\Models\Term;
use Cache;
use Util;

/**
 * Класс действия для получения термина.
 */
class TermGetAction extends Action
{
    /**
     * ID термина.
     *
     * @var int|string
     */
    private int|string $id;

    /***
     * @param int|string $id ID термина.
     */
    public function __construct(int|string $id)
    {
        $this->id = $id;
    }

    /**
     * Метод запуска логики.
     *
     * @return TermEntity|null Вернет результаты исполнения.
     */
    public function run(): ?TermEntity
    {
        $cacheKey = Util::getKey('term', $this->id);

        return Cache::tags(['term'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $term = Term::where('id', $this->id)
                    ->first();

                return $term ? TermEntity::from($term->toArray()) : null;
            }
        );
    }
}
