<?php
/**
 * Модуль Разделов.
 * Этот модуль содержит все классы для работы с разделами каталога.
 *
 * @package App\Modules\Section
 */

namespace App\Modules\Section\Actions\Admin;

use DB;
use Cache;
use Config;
use Throwable;
use Typography;
use App\Models\Action;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Metatag\Template\TemplateException;
use App\Modules\Section\Entities\Section as SectionEntity;
use App\Modules\Section\Models\Section;
use App\Modules\Metatag\Actions\MetatagSetAction;
use App\Modules\Metatag\Data\MetatagSet;
use App\Modules\Section\Data\SectionUpdate;
use App\Modules\Section\Entities\SectionItem as SectionItemEntity;
use App\Modules\Section\Models\SectionItem;
use App\Modules\Analyzer\Actions\Admin\AnalyzerUpdateAction;

/**
 * Класс действия для обновления раздела.
 */
class SectionUpdateAction extends Action
{
    /**
     * @var SectionUpdate Данные для создания раздела.
     */
    private SectionUpdate $data;

    /**
     * @param SectionUpdate $data Данные для создания раздела.
     */
    public function __construct(SectionUpdate $data)
    {
        $this->data = $data;
    }

    /**
     * Метод запуска логики.
     *
     * @return SectionEntity Вернет результаты исполнения.
     * @throws RecordNotExistException
     * @throws TemplateException
     * @throws Throwable
     */
    public function run(): SectionEntity
    {
        $action = new SectionGetAction($this->data->id);
        $sectionEntity = $action->run();

        if ($sectionEntity) {
            DB::transaction(function () use ($sectionEntity) {
                $action = new MetatagSetAction(MetatagSet::from([
                    'description' => Typography::process($this->data->description, true),
                    'title' => Typography::process($this->data->title, true),
                    'keywords' => $this->data->keywords,
                    'id' => $sectionEntity->metatag_id ?: null,
                ]));

                $sectionEntityData = SectionEntity::from([
                    ...$sectionEntity->toArray(),
                    ...$this->data->toArray(),
                    'metatag_id' => $action->run()->id,
                    'name' => Typography::process($this->data->name, true),
                    'header' => Typography::process($this->data->header, true),
                    'text' => Typography::process($this->data->text),
                    'additional' => Typography::process($this->data->additional),
                ]);

                Section::find($this->data->id)->update($sectionEntityData->toArray());

                SectionItem::whereIn('id', collect($sectionEntity->items)->pluck('id')->toArray())
                    ->forceDelete();

                $weight = 0;
                $items = Config::get('section.items');

                foreach ($this->data->items as $item) {
                    $sectionItemEntity = SectionItemEntity::from([
                        'section_id' => $sectionEntity->id,
                        'weight' => $weight,
                        'itemable_id' => $item['id'],
                        'itemable_type' => $items[$item['type']],
                    ]);

                    SectionItem::create($sectionItemEntity->toArray());

                    $weight++;
                }

                Cache::tags(['catalog', 'section'])->flush();

                $action = new AnalyzerUpdateAction($sectionEntity->id, Section::class, 'section.text');
                $action->run();
            });

            $action = new SectionGetAction($this->data->id);

            return $action->run();
        }

        throw new RecordNotExistException(
            trans('skill::actions.admin.skillUpdateAction.notExistSkill')
        );
    }
}
