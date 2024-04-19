<?php
/**
 * Модуль Термином.
 * Этот модуль содержит все классы для работы с терминами.
 *
 * @package App\Modules\Term
 */

namespace App\Modules\Term\Actions\Admin;

use App\Models\Action;
use App\Modules\Term\Models\Term;
use Cache;

/**
 * Класс действия для удаления термина.
 */
class TermDestroyAction extends Action
{
    /**
     * Массив ID терминов.
     *
     * @var int[]|string[]
     */
    private array $ids;

    /**
     * @param int[]|string[] $ids Массив ID терминов.
     */
    public function __construct(array $ids)
    {
        $this->ids = $ids;
    }

    /**
     * Метод запуска логики.
     *
     * @return bool Вернет результаты исполнения.
     */
    public function run(): bool
    {
        if ($this->ids) {
            Term::destroy($this->ids);
            Cache::tags(['term'])->flush();
        }

        return true;
    }
}
