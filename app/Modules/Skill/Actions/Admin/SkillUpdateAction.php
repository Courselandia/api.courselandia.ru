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
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Metatag\Template\Template;
use App\Modules\Metatag\Template\TemplateException;
use App\Modules\Skill\Entities\Skill as SkillEntity;
use App\Modules\Skill\Models\Skill;
use App\Modules\Metatag\Actions\MetatagSetAction;
use Cache;

/**
 * Класс действия для обновления навыков.
 */
class SkillUpdateAction extends Action
{
    /**
     * ID навыка.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

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
    public ?string $description_template = null;

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
    public ?string $title_template = null;

    /**
     * Метод запуска логики.
     *
     * @return SkillEntity Вернет результаты исполнения.
     * @throws RecordNotExistException
     * @throws ParameterInvalidException
     * @throws TemplateException
     */
    public function run(): SkillEntity
    {
        $action = app(SkillGetAction::class);
        $action->id = $this->id;
        $skillEntity = $action->run();

        if ($skillEntity) {
            $templateValues = [];

            $template = new Template();

            $action = app(MetatagSetAction::class);
            $action->description = $template->convert($this->description_template, $templateValues);
            $action->title = $template->convert($this->title_template, $templateValues);
            $action->description_template = $this->description_template;
            $action->title_template = $this->title_template;
            $action->keywords = $this->keywords;
            $action->id = $skillEntity->metatag_id ?: null;

            $skillEntity->metatag_id = $action->run()->id;
            $skillEntity->id = $this->id;
            $skillEntity->name = $this->name;
            $skillEntity->header = $template->convert($this->header_template, $templateValues);
            $skillEntity->header_template = $this->header_template;
            $skillEntity->link = $this->link;
            $skillEntity->text = $this->text;
            $skillEntity->status = $this->status;

            Skill::find($this->id)->update($skillEntity->toArray());
            Cache::tags(['catalog', 'skill'])->flush();

            $action = app(SkillGetAction::class);
            $action->id = $this->id;

            return $action->run();
        }

        throw new RecordNotExistException(
            trans('skill::actions.admin.skillUpdateAction.notExistSkill')
        );
    }
}
