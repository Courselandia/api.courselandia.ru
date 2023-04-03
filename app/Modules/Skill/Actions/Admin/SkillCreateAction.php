<?php
/**
 * Модуль Навыков.
 * Этот модуль содержит все классы для работы с навыками.
 *
 * @package App\Modules\Skill
 */

namespace App\Modules\Skill\Actions\Admin;

use App\Models\Action;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Metatag\Template\Template;
use App\Modules\Metatag\Template\TemplateException;
use App\Modules\Skill\Entities\Skill as SkillEntity;
use App\Modules\Skill\Models\Skill;
use App\Modules\Metatag\Actions\MetatagSetAction;
use Cache;

/**
 * Класс действия для создания навыка.
 */
class SkillCreateAction extends Action
{
    /**
     * Название.
     *
     * @var string|null
     */
    public ?string $name = null;

    /**
     * Шаблон заголовка.
     *
     * @var string|null
     */
    public ?string $header_template = null;

    /**
     * Ссылка.
     *
     * @var string|null
     */
    public ?string $link = null;

    /**
     * Статья.
     *
     * @var string|null
     */
    public ?string $text = null;

    /**
     * Статус.
     *
     * @var bool|null
     */
    public ?bool $status = null;

    /**
     * Шаблон описания.
     *
     * @var string|null
     */
    public ?string $template_description = null;

    /**
     * Ключевые слова.
     *
     * @var string|null
     */
    public ?string $keywords = null;

    /**
     * Шаблон заголовка.
     *
     * @var string|null
     */
    public ?string $template_title = null;

    /**
     * Метод запуска логики.
     *
     * @return SkillEntity Вернет результаты исполнения.
     * @throws ParameterInvalidException
     * @throws TemplateException
     */
    public function run(): SkillEntity
    {
        $action = app(MetatagSetAction::class);
        $template = new Template();

        $templateValues = [];

        $action->description = $template->convert($this->template_description, $templateValues);
        $action->title = $template->convert($this->template_title, $templateValues);
        $action->template_description = $this->template_description;
        $action->template_title = $this->template_title;
        $action->keywords = $this->keywords;

        $metatag = $action->run();

        $skillEntity = new SkillEntity();
        $skillEntity->name = $this->name;
        $skillEntity->header = $template->convert($this->header_template, $templateValues);
        $skillEntity->link = $this->link;
        $skillEntity->text = $this->text;
        $skillEntity->status = $this->status;
        $skillEntity->metatag_id = $metatag->id;

        $skill = Skill::create($skillEntity->toArray());
        Cache::tags(['catalog', 'skill'])->flush();

        $action = app(SkillGetAction::class);
        $action->id = $skill->id;

        return $action->run();
    }
}
