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
use App\Modules\Skill\Data\SkillUpdate;
use Cache;
use Throwable;
use Typography;
use App\Models\Action;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Course\Enums\Status;
use App\Modules\Course\Models\Course;
use App\Modules\Metatag\Template\Template;
use App\Modules\Metatag\Template\TemplateException;
use App\Modules\Skill\Entities\Skill as SkillEntity;
use App\Modules\Skill\Models\Skill;
use App\Modules\Metatag\Actions\MetatagSetAction;
use App\Modules\Analyzer\Actions\Admin\AnalyzerUpdateAction;

/**
 * Класс действия для обновления навыков.
 */
class SkillUpdateAction extends Action
{
    /**
     * @var SkillUpdate Данные для создания навыка.
     */
    private SkillUpdate $data;

    /**
     * @param SkillUpdate $data Данные для создания навыка.
     */
    public function __construct(SkillUpdate $data)
    {
        $this->data = $data;
    }

    /**
     * Метод запуска логики.
     *
     * @return SkillEntity Вернет результаты исполнения.
     * @throws RecordNotExistException
     * @throws TemplateException
     * @throws Throwable
     */
    public function run(): SkillEntity
    {
        $action = new SkillGetAction($this->data->id);
        $skillEntity = $action->run();

        if ($skillEntity) {
            DB::transaction(function () use ($skillEntity) {
                $countSkillCourses = Course::where('courses.status', Status::ACTIVE->value)
                    ->whereHas('school', function ($query) {
                        $query->active()
                            ->hasCourses();
                    })
                    ->whereHas('skills', function ($query) {
                        $query->where('skills.id', $this->data->id);
                    })
                    ->count();

                $templateValues = [
                    'skill' => $this->data->name,
                    'countSkillCourses' => $countSkillCourses,
                ];

                $template = new Template();

                $action = new MetatagSetAction(MetatagSet::from([
                    'description' => Typography::process($template->convert($this->data->description_template, $templateValues), true),
                    'title' => Typography::process($template->convert($this->data->title_template, $templateValues), true),
                    'description_template' => $this->data->description_template,
                    'title_template' => $this->data->title_template,
                    'keywords' => $this->data->keywords,
                    'id' => $skillEntity->metatag_id ?: null,
                ]));

                $skillEntity = SkillEntity::from([
                    ...$skillEntity->toArray(),
                    ...$this->data->toArray(),
                    'metatag_id' => $action->run()->id,
                    'name' => Typography::process($this->data->name, true),
                    'header' => Typography::process($template->convert($this->data->header_template, $templateValues), true),
                    'text' => Typography::process($this->data->text),
                    'additional' => Typography::process($this->data->additional),
                ]);

                Skill::find($this->data->id)->update($skillEntity->toArray());
                Cache::tags(['catalog', 'skill'])->flush();

                $action = new AnalyzerUpdateAction($skillEntity->id, Skill::class, 'skill.text');
                $action->run();
            });

            $action = new SkillGetAction($this->data->id);

            return $action->run();
        }

        throw new RecordNotExistException(
            trans('skill::actions.admin.skillUpdateAction.notExistSkill')
        );
    }
}
