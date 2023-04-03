<?php
/**
 * Модуль Инструментов.
 * Этот модуль содержит все классы для работы с инструментами.
 *
 * @package App\Modules\Tool
 */

namespace App\Modules\Tool\Actions\Admin;

use App\Models\Action;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Metatag\Template\Template;
use App\Modules\Metatag\Template\TemplateException;
use App\Modules\Tool\Entities\Tool as ToolEntity;
use App\Modules\Tool\Models\Tool;
use App\Modules\Metatag\Actions\MetatagSetAction;
use Cache;

/**
 * Класс действия для создания инструмента.
 */
class ToolCreateAction extends Action
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
     * @return ToolEntity Вернет результаты исполнения.
     * @throws ParameterInvalidException
     * @throws TemplateException
     */
    public function run(): ToolEntity
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

        $toolEntity = new ToolEntity();
        $toolEntity->name = $this->name;
        $toolEntity->header = $template->convert($this->header_template, $templateValues);
        $toolEntity->link = $this->link;
        $toolEntity->text = $this->text;
        $toolEntity->status = $this->status;
        $toolEntity->metatag_id = $metatag->id;

        $tool = Tool::create($toolEntity->toArray());
        Cache::tags(['catalog', 'tool'])->flush();

        $action = app(ToolGetAction::class);
        $action->id = $tool->id;

        return $action->run();
    }
}
