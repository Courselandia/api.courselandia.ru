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
use App\Modules\Metatag\Data\MetatagSet;
use App\Modules\Metatag\Models\Metatag;
use App\Modules\Metatag\Entities\Metatag as MetatagEntity;

/**
 * Класс для установки метатэгов.
 */
class MetatagSetAction extends Action
{
    /**
     * Данные для становки метатэгов.
     *
     * @var MetatagSet
     */
    private MetatagSet $data;

    public function __construct(MetatagSet $data)
    {
        $this->data = $data;
    }

    /**
     * Метод запуска логики.
     *
     * @return MetatagEntity Вернет результаты исполнения.
     * @throws ParameterInvalidException
     */
    public function run(): MetatagEntity
    {
        $metatagEntity = new MetatagEntity();
        $metatagEntity->description = $this->data->description;
        $metatagEntity->keywords = $this->data->keywords;
        $metatagEntity->title = $this->data->title;
        $metatagEntity->description_template = $this->data->description_template;
        $metatagEntity->title_template = $this->data->title_template;

        if ($this->data->id) {
            $metatag = Metatag::find($this->data->id);

            if ($metatag) {
                $metatagEntity->id = $this->data->id;
                $metatag->update($metatagEntity->toArray());

                return MetatagEntity::from($metatagEntity->toArray());
            }
        }

        $metatag = Metatag::create($metatagEntity->toArray());

        return MetatagEntity::from($metatag->toArray());
    }
}
