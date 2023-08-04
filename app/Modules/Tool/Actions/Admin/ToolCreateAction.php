<?php
/**
 * Модуль Инструментов.
 * Этот модуль содержит все классы для работы с инструментами.
 *
 * @package App\Modules\Tool
 */

namespace App\Modules\Tool\Actions\Admin;

use Cache;
use Typography;
use App\Models\Action;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Metatag\Template\Template;
use App\Modules\Metatag\Template\TemplateException;
use App\Modules\Tool\Entities\Tool as ToolEntity;
use App\Modules\Tool\Models\Tool;
use App\Modules\Metatag\Actions\MetatagSetAction;
use App\Modules\Analyzer\Actions\Admin\AnalyzerUpdateAction;

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
     * @return ToolEntity Вернет результаты исполнения.
     * @throws ParameterInvalidException
     * @throws TemplateException
     */
    public function run(): ToolEntity
    {
        $action = app(MetatagSetAction::class);
        $template = new Template();

        $templateValues = [
            'tool' => $this->name,
            'countToolCourses' => 0,
        ];

        $action->description = $template->convert($this->description_template, $templateValues);
        $action->title = $template->convert($this->title_template, $templateValues);
        $action->description_template = $this->description_template;
        $action->title_template = $this->title_template;
        $action->keywords = $this->keywords;

        $metatag = $action->run();

        $toolEntity = new ToolEntity();
        $toolEntity->name = Typography::process($this->name, true);
        $toolEntity->header = Typography::process($template->convert($this->header_template, $templateValues), true);
        $toolEntity->header_template = $this->header_template;
        $toolEntity->link = $this->link;
        $toolEntity->text = Typography::process($this->text);
        $toolEntity->status = $this->status;
        $toolEntity->metatag_id = $metatag->id;

        $tool = Tool::create($toolEntity->toArray());
        Cache::tags(['catalog', 'tool'])->flush();

        $action = app(AnalyzerUpdateAction::class);
        $action->id = $tool->id;
        $action->model = Tool::class;
        $action->category = 'tool.text';
        $action->run();

        $action = app(ToolGetAction::class);
        $action->id = $tool->id;

        return $action->run();
    }
}
