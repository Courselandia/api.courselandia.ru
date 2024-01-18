<?php
/**
 * Модуль Направления.
 * Этот модуль содержит все классы для работы с направлениями.
 *
 * @package App\Modules\Direction
 */

namespace App\Modules\Direction\Actions\Admin;

use App\Models\Action;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Analyzer\Actions\Admin\AnalyzerUpdateAction;
use App\Modules\Direction\Data\DirectionCreate;
use App\Modules\Direction\Entities\Direction as DirectionEntity;
use App\Modules\Direction\Models\Direction;
use App\Modules\Metatag\Actions\MetatagSetAction;
use App\Modules\Metatag\Data\MetatagSet;
use App\Modules\Metatag\Template\Template;
use App\Modules\Metatag\Template\TemplateException;
use Cache;
use Typography;

/**
 * Класс действия для создания направления.
 */
class DirectionCreateAction extends Action
{
    /**
     * Сущность для создания направления.
     *
     * @var DirectionCreate
     */
    private DirectionCreate $data;

    /**
     * @param DirectionCreate $data Сущность для создания направления.
     */
    public function __construct(DirectionCreate $data)
    {
        $this->data = $data;
    }

    /**
     * Метод запуска логики.
     *
     * @return DirectionEntity Вернет результаты исполнения.
     * @throws ParameterInvalidException|TemplateException
     */
    public function run(): DirectionEntity
    {
        $template = new Template();

        $templateValues = [
            'direction' => $this->data->name,
            'countDirectionCourses' => 0,
        ];

        $metatagSet = MetatagSet::from([
            'description' => $template->convert($this->data->description_template, $templateValues),
            'title' => $template->convert($this->data->title_template, $templateValues),
            'description_template' => $this->data->description_template,
            'title_template' => $this->data->title_template,
            'keywords' => $this->data->keywords,
        ]);

        $action = new MetatagSetAction($metatagSet);
        $metatag = $action->run();

        $directionEntity = DirectionEntity::from([
            'name' => Typography::process($this->data->name, true),
            'header' => Typography::process($template->convert($this->data->header_template, $templateValues), true),
            'header_template' => $this->data->header_template,
            'weight' => $this->data->weight,
            'link' => $this->data->link,
            'text' => Typography::process($this->data->text),
            'status' => $this->data->status,
            'metatag_id' => $metatag->id,
        ]);

        $direction = Direction::create($directionEntity->toArray());

        Cache::tags(['catalog', 'category', 'direction', 'profession', 'teacher'])->flush();

        $action = new AnalyzerUpdateAction($direction->id, Direction::class, 'direction.text');
        $action->run();

        $action = new DirectionGetAction($direction->id);

        return $action->run();
    }
}
