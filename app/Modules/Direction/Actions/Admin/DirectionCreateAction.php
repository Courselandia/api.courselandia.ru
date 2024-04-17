<?php
/**
 * Модуль Направления.
 * Этот модуль содержит все классы для работы с направлениями.
 *
 * @package App\Modules\Direction
 */

namespace App\Modules\Direction\Actions\Admin;

use DB;
use App\Models\Action;
use App\Modules\Analyzer\Actions\Admin\AnalyzerUpdateAction;
use App\Modules\Direction\Data\DirectionCreate;
use App\Modules\Direction\Entities\Direction as DirectionEntity;
use App\Modules\Direction\Models\Direction;
use App\Modules\Metatag\Actions\MetatagSetAction;
use App\Modules\Metatag\Data\MetatagSet;
use App\Modules\Metatag\Template\Template;
use App\Modules\Metatag\Template\TemplateException;
use Cache;
use Throwable;
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
     * @throws TemplateException
     * @throws Throwable
     */
    public function run(): DirectionEntity
    {
        $id = DB::transaction(function () {
            $template = new Template();

            $templateValues = [
                'direction' => $this->data->name,
                'countDirectionCourses' => 0,
            ];

            $metatagSet = MetatagSet::from([
                'description' => Typography::process($template->convert($this->data->description_template, $templateValues), true),
                'title' => Typography::process($template->convert($this->data->title_template, $templateValues), true),
                'description_template' => $this->data->description_template,
                'title_template' => $this->data->title_template,
                'keywords' => $this->data->keywords,
            ]);

            $action = new MetatagSetAction($metatagSet);
            $metatag = $action->run();

            $directionEntity = DirectionEntity::from([
                ...$this->data->toArray(),
                'name' => Typography::process($this->data->name, true),
                'header' => Typography::process($template->convert($this->data->header_template, $templateValues), true),
                'text' => Typography::process($this->data->text),
                'additional' => Typography::process($this->data->additional),
                'metatag_id' => $metatag->id,
            ]);

            $direction = Direction::create($directionEntity->toArray());

            Cache::tags(['catalog', 'direction'])->flush();

            $action = new AnalyzerUpdateAction($direction->id, Direction::class, 'direction.text');
            $action->run();

            return $direction->id;
        });

        $action = new DirectionGetAction($id);

        return $action->run();
    }
}
