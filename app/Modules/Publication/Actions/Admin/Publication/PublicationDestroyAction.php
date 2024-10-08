<?php
/**
 * Модуль Публикации.
 * Этот модуль содержит все классы для работы с публикациями.
 *
 * @package App\Modules\Publication
 */

namespace App\Modules\Publication\Actions\Admin\Publication;

use App\Models\Action;
use App\Modules\Publication\Models\Publication;
use Cache;

/**
 * Класс действия для удаления публикации.
 */
class PublicationDestroyAction extends Action
{
    /**
     * Массив ID публикаций.
     *
     * @var int[]|string[]
     */
    private array $ids;

    /**
     * @param int[]|string[] $ids Массив ID публикаций.
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
            Publication::destroy($this->ids);
            Cache::tags(['publication'])->flush();
        }

        return true;
    }
}
