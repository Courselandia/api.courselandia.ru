<?php
/**
 * Модуль Метатэги.
 * Этот модуль содержит все классы для работы с метатегами.
 *
 * @package App\Modules\Metatag
 */

namespace App\Modules\Metatag\Actions;

use App\Models\Action;
use App\Modules\Metatag\Entities\Metatag as MetatagEntity;
use App\Modules\Metatag\Models\Metatag;

/**
 * Класс для получения метатэгов.
 */
class MetatagGetAction extends Action
{
    /**
     * ID метатэга.
     *
     * @var int|string
     */
    private int|string $id;

    public function __construct(int|string $id)
    {
        $this->id = $id;
    }

    /**
     * Метод запуска логики.
     *
     * @return MetatagEntity|null Вернет результаты исполнения.
     */
    public function run(): ?MetatagEntity
    {
        $metatag = Metatag::find($this->id);

        return $metatag ? MetatagEntity::from($metatag->toArray()) : null;
    }
}
