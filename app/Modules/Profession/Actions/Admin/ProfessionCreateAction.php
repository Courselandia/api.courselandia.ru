<?php
/**
 * Модуль Профессии.
 * Этот модуль содержит все классы для работы с профессиями.
 *
 * @package App\Modules\Profession
 */

namespace App\Modules\Profession\Actions\Admin;

use App\Models\Action;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Metatag\Template\Template;
use App\Modules\Metatag\Template\TemplateException;
use App\Modules\Profession\Entities\Profession as ProfessionEntity;
use App\Modules\Profession\Models\Profession;
use App\Modules\Metatag\Actions\MetatagSetAction;
use Cache;

/**
 * Класс действия для создания профессии.
 */
class ProfessionCreateAction extends Action
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
     * @return ProfessionEntity Вернет результаты исполнения.
     * @throws ParameterInvalidException
     * @throws TemplateException
     */
    public function run(): ProfessionEntity
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

        $professionEntity = new ProfessionEntity();
        $professionEntity->name = $this->name;
        $professionEntity->header = $template->convert($this->header_template, $templateValues);
        $professionEntity->link = $this->link;
        $professionEntity->text = $this->text;
        $professionEntity->status = $this->status;
        $professionEntity->metatag_id = $metatag->id;

        $profession = Profession::create($professionEntity->toArray());
        Cache::tags(['catalog', 'category', 'direction', 'salary', 'profession'])->flush();

        $action = app(ProfessionGetAction::class);
        $action->id = $profession->id;

        return $action->run();
    }
}
