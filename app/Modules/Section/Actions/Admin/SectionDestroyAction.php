<?php
/**
 * Модуль Разделов.
 * Этот модуль содержит все классы для работы с разделами каталога.
 *
 * @package App\Modules\Section
 */

namespace App\Modules\Section\Actions\Admin;

use App\Models\Action;
use App\Modules\Section\Models\Section;
use Cache;

/**
 * Класс действия для удаления раздела.
 */
class SectionDestroyAction extends Action
{
    /**
     * Массив ID разделов.
     *
     * @var int[]|string[]
     */
    private array $ids;

    /**
     * @param int[]|string[] $ids Массив ID разделов.
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
            Section::destroy($this->ids);
            Cache::tags(['section'])->flush();
        }

        return true;
    }
}
