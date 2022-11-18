<?php
/**
 * Модуль Метатэги.
 * Этот модуль содержит все классы для работы с метатегами.
 *
 * @package App\Modules\Metatag
 */

namespace App\Modules\Metatag\Actions;

use App\Models\Action;
use App\Models\Exceptions\ParameterInvalidException;
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
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * Метод запуска логики.
     *
     * @return MetatagEntity|null Вернет результаты исполнения.
     * @throws ParameterInvalidException
     */
    public function run(): ?MetatagEntity
    {
        $metatag = Metatag::find($this->id);

        return $metatag ? new MetatagEntity($metatag->toArray()) : null;
    }
}
