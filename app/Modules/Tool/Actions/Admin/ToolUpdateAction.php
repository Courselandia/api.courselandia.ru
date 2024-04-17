<?php
/**
 * Модуль Инструментов.
 * Этот модуль содержит все классы для работы с инструментами.
 *
 * @package App\Modules\Tool
 */

namespace App\Modules\Tool\Actions\Admin;

use DB;
use App\Modules\Metatag\Data\MetatagSet;
use App\Modules\Tool\Data\ToolUpdate;
use Cache;
use Throwable;
use Typography;
use App\Models\Action;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Course\Enums\Status;
use App\Modules\Course\Models\Course;
use App\Modules\Metatag\Template\Template;
use App\Modules\Metatag\Template\TemplateException;
use App\Modules\Tool\Entities\Tool as ToolEntity;
use App\Modules\Tool\Models\Tool;
use App\Modules\Metatag\Actions\MetatagSetAction;
use App\Modules\Analyzer\Actions\Admin\AnalyzerUpdateAction;

/**
 * Класс действия для обновления инструментов.
 */
class ToolUpdateAction extends Action
{
    /**
     * Данные для обновления инструмента.
     *
     * @var ToolUpdate
     */
    private ToolUpdate $data;

    /**
     * @param ToolUpdate $data Данные для обновления инструмента.
     */
    public function __construct(ToolUpdate $data)
    {
        $this->data = $data;
    }

    /**
     * Метод запуска логики.
     *
     * @return ToolEntity Вернет результаты исполнения.
     * @throws RecordNotExistException
     * @throws TemplateException
     * @throws Throwable
     */
    public function run(): ToolEntity
    {
        $action = new ToolGetAction($this->data->id);
        $toolEntity = $action->run();

        if ($toolEntity) {
            DB::transaction(function () use ($toolEntity) {
                $countToolCourses = Course::where('courses.status', Status::ACTIVE->value)
                    ->whereHas('school', function ($query) {
                        $query->where('schools.status', true);
                    })
                    ->whereHas('tools', function ($query) {
                        $query->where('tools.id', $this->data->id);
                    })
                    ->count();

                $templateValues = [
                    'tool' => $this->data->name,
                    'countToolCourses' => $countToolCourses,
                ];

                $template = new Template();

                $action = new MetatagSetAction(MetatagSet::from([
                    'description' => Typography::process($template->convert($this->data->description_template, $templateValues), true),
                    'title' => Typography::process($template->convert($this->data->title_template, $templateValues), true),
                    'description_template' => $this->data->description_template,
                    'title_template' => $this->data->title_template,
                    'keywords' => $this->data->keywords,
                    'id' => $toolEntity->metatag_id ?: null,
                ]));

                $toolEntity = ToolEntity::from([
                    ...$toolEntity->toArray(),
                    ...$this->data->toArray(),
                    'metatag_id' => $action->run()->id,
                    'name' => Typography::process($this->data->name, true),
                    'header' => Typography::process($template->convert($this->data->header_template, $templateValues), true),
                    'text' => Typography::process($this->data->text),
                    'additional' => Typography::process($this->data->additional),
                ]);

                Tool::find($this->data->id)->update($toolEntity->toArray());
                Cache::tags(['catalog', 'tool'])->flush();

                $action = new AnalyzerUpdateAction($toolEntity->id, Tool::class, 'tool.text');
                $action->run();
            });

            $action = new ToolGetAction($this->data->id);

            return $action->run();
        }

        throw new RecordNotExistException(
            trans('tool::actions.admin.toolUpdateAction.notExistTool')
        );
    }
}
