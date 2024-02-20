<?php
/**
 * Модуль Разделов.
 * Этот модуль содержит все классы для работы с разделами каталога.
 *
 * @package App\Modules\Section
 */

namespace App\Modules\Section\Actions\Admin;

use Cache;
use Typography;
use App\Models\Action;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Metatag\Template\TemplateException;
use App\Modules\Section\Entities\Section as SectionEntity;
use App\Modules\Section\Entities\SectionItem as SectionItemEntity;
use App\Modules\Section\Models\Section;
use App\Modules\Section\Models\SectionItem;
use App\Modules\Metatag\Actions\MetatagSetAction;
use App\Modules\Metatag\Data\MetatagSet;
use App\Modules\Section\Data\SectionCreate;

/**
 * Класс действия для создания раздела.
 */
class SectionCreateAction extends Action
{
    /**
     * Данные для создания раздела.
     *
     * @var SectionCreate
     */
    private SectionCreate $data;

    /**
     * @param SectionCreate $data Данные для создания раздела.
     */
    public function __construct(SectionCreate $data)
    {
        $this->data = $data;
    }

    /**
     * Метод запуска логики.
     *
     * @return SectionEntity Вернет результаты исполнения.
     * @throws ParameterInvalidException
     * @throws TemplateException
     */
    public function run(): SectionEntity
    {
        $action = new MetatagSetAction(MetatagSet::from([
            'description' => $this->data->description,
            'title' => $this->data->title,
            'keywords' => $this->data->keywords,
        ]));

        $metatag = $action->run();

        $sectionEntity = SectionEntity::from([
            ...$this->data->toArray(),
            'name' => Typography::process($this->data->name, true),
            'header' => Typography::process($this->data->header, true),
            'text' => Typography::process($this->data->text),
            'additional' => Typography::process($this->data->additional),
            'metatag_id' => $metatag->id,
        ]);

        $section = Section::create($sectionEntity->toArray());
        $weight = 0;

        foreach ($this->data->items as $item) {
            $sectionItemEntity = SectionItemEntity::from([
                'section_id' => $sectionEntity->id,
                'weight' => $weight,
                'itemable_id' => $item['id'],
                'itemable_type' => $item['type'],
            ]);

            SectionItem::create($sectionItemEntity->toArray());

            $weight++;
        }

        Cache::tags(['section'])->flush();

        $action = new SectionGetAction($section->id);

        return $action->run();
    }
}
