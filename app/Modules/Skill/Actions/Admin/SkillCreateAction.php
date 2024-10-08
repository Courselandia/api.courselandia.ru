<?php
/**
 * Модуль Навыков.
 * Этот модуль содержит все классы для работы с навыками.
 *
 * @package App\Modules\Skill
 */

namespace App\Modules\Skill\Actions\Admin;

use DB;
use App\Modules\Metatag\Data\MetatagSet;
use App\Modules\Skill\Data\SkillCreate;
use Cache;
use Throwable;
use Typography;
use App\Models\Action;
use App\Modules\Metatag\Template\Template;
use App\Modules\Metatag\Template\TemplateException;
use App\Modules\Skill\Entities\Skill as SkillEntity;
use App\Modules\Skill\Models\Skill;
use App\Modules\Metatag\Actions\MetatagSetAction;
use App\Modules\Analyzer\Actions\Admin\AnalyzerUpdateAction;

/**
 * Класс действия для создания навыка.
 */
class SkillCreateAction extends Action
{
    /**
     * Данные для создания навыка.
     *
     * @var SkillCreate
     */
    private SkillCreate $data;

    /**
     * @param SkillCreate $data Данные для создания навыка.
     */
    public function __construct(SkillCreate $data)
    {
        $this->data = $data;
    }

    /**
     * Метод запуска логики.
     *
     * @return SkillEntity Вернет результаты исполнения.
     * @throws TemplateException
     * @throws Throwable
     */
    public function run(): SkillEntity
    {
        $id = DB::transaction(function () {
            $template = new Template();

            $templateValues = [
                'skill' => $this->data->name,
                'countSkillCourses' => 0,
            ];

            $action = new MetatagSetAction(MetatagSet::from([
                'description' => Typography::process($template->convert($this->data->description_template, $templateValues), true),
                'title' => Typography::process($template->convert($this->data->title_template, $templateValues), true),
                'description_template' => $this->data->description_template,
                'title_template' => $this->data->title_template,
                'keywords' => $this->data->keywords,
            ]));

            $metatag = $action->run();

            $skillEntity = SkillEntity::from([
                ...$this->data->toArray(),
                'name' => Typography::process($this->data->name, true),
                'header' => Typography::process($template->convert($this->data->header_template, $templateValues), true),
                'text' => Typography::process($this->data->text),
                'additional' => Typography::process($this->data->additional),
                'metatag_id' => $metatag->id,
            ]);

            $skill = Skill::create($skillEntity->toArray());
            Cache::tags(['catalog', 'skill'])->flush();

            $action = new AnalyzerUpdateAction($skill->id, Skill::class, 'skill.text');
            $action->run();

            return $skill->id;
        });

        $action = new SkillGetAction($id);

        return $action->run();
    }
}
