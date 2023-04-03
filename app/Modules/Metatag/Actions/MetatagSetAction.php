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
use App\Modules\Metatag\Models\Metatag;
use App\Modules\Metatag\Entities\Metatag as MetatagEntity;

/**
 * Класс для установки метатэгов.
 */
class MetatagSetAction extends Action
{
    /**
     * ID записи.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * Описание.
     *
     * @var string|null
     */
    public ?string $description = null;

    /**
     * Ключевые слова.
     *
     * @var string|null
     */
    public ?string $keywords = null;

    /**
     * Заголовок.
     *
     * @var string|null
     */
    public ?string $title = null;

    /**
     * Шаблон заголовок.
     *
     * @var string|null
     */
    public ?string $template_title = null;

    /**
     * Шаблон описания.
     *
     * @var string|null
     */
    public ?string $template_description = null;

    /**
     * Метод запуска логики.
     *
     * @return MetatagEntity Вернет результаты исполнения.
     * @throws ParameterInvalidException
     */
    public function run(): MetatagEntity
    {
        $metatagEntity = new MetatagEntity();
        $metatagEntity->description = $this->description;
        $metatagEntity->keywords = $this->keywords;
        $metatagEntity->title = $this->title;
        $metatagEntity->template_description = $this->template_description;
        $metatagEntity->template_title = $this->template_title;

        if ($this->id) {
            $metatag = Metatag::find($this->id);

            if ($metatag) {
                $metatagEntity->id = $this->id;
                $metatag->update($metatagEntity->toArray());

                return new MetatagEntity($metatagEntity->toArray());
            }
        }

        $metatag = Metatag::create($metatagEntity->toArray());

        return new MetatagEntity($metatag->toArray());
    }
}
