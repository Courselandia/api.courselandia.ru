<?php
/**
 * Модуль Инструментов.
 * Этот модуль содержит все классы для работы с инструментами.
 *
 * @package App\Modules\Tool
 */

namespace App\Modules\Tool\Actions\Admin;

use App\Modules\Metatag\Data\MetatagSet;
use App\Modules\Tool\Data\ToolCreate;
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
     * Данные для создания инструмента.
     *
     * @var ToolCreate
     */
    private ToolCreate $data;

    /**
     * @param ToolCreate $data Данные для создания инструмента.
     */
    public function __construct(ToolCreate $data)
    {
        $this->data = $data;
    }

    /**
     * Метод запуска логики.
     *
     * @return ToolEntity Вернет результаты исполнения.
     * @throws ParameterInvalidException
     * @throws TemplateException
     */
    public function run(): ToolEntity
    {
        $template = new Template();

        $templateValues = [
            'tool' => $this->data->name,
            'countToolCourses' => 0,
        ];

        $action = new MetatagSetAction(MetatagSet::from([
            'description' => $template->convert($this->data->description_template, $templateValues),
            'title' => $template->convert($this->data->title_template, $templateValues),
            'description_template' => $this->data->description_template,
            'title_template' => $this->data->title_template,
            'keywords' => $this->data->keywords,
        ]));

        $metatag = $action->run();

        $toolEntity = ToolEntity::from([
            ...$this->data->toArray(),
            'name' => Typography::process($this->data->name, true),
            'header' => Typography::process($template->convert($this->data->header_template, $templateValues), true),
            'text' => Typography::process($this->data->text),
            'additional' => Typography::process($this->data->additional),
            'metatag_id' => $metatag->id,
        ]);

        $tool = Tool::create($toolEntity->toArray());
        Cache::tags(['catalog', 'tool'])->flush();

        $action = new AnalyzerUpdateAction($tool->id, Tool::class, 'tool.text');
        $action->run();

        $action = new ToolGetAction($tool->id);

        return $action->run();
    }
}
