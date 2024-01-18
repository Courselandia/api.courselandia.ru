<?php
/**
 * Модуль Направления.
 * Этот модуль содержит все классы для работы с направлениями.
 *
 * @package App\Modules\Direction
 */

namespace App\Modules\Direction\Actions\Admin;

use App\Modules\Analyzer\Actions\Admin\AnalyzerUpdateAction;
use App\Modules\Direction\Data\DirectionUpdate;
use Cache;
use Typography;
use App\Models\Action;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Course\Enums\Status;
use App\Modules\Course\Models\Course;
use App\Modules\Direction\Entities\Direction as DirectionEntity;
use App\Modules\Direction\Models\Direction;
use App\Modules\Metatag\Actions\MetatagSetAction;
use App\Modules\Metatag\Template\Template;
use App\Modules\Metatag\Template\TemplateException;

/**
 * Класс действия для обновления направлений.
 */
class DirectionUpdateAction extends Action
{
    /**
     * Сущность для обновления направления.
     *
     * @var DirectionUpdate
     */
    private DirectionUpdate $data;

    /**
     * @param DirectionUpdate $data Сущность для обновления направления.
     */
    public function __construct(DirectionUpdate $data)
    {
        $this->data = $data;
    }

    /**
     * Метод запуска логики.
     *
     * @return DirectionEntity Вернет результаты исполнения.
     * @throws RecordNotExistException
     * @throws ParameterInvalidException
     * @throws TemplateException
     */
    public function run(): DirectionEntity
    {
        $action = new DirectionGetAction($this->data->id);
        $directionEntity = $action->run();

        if ($directionEntity) {
            $countDirectionCourses = Course::where('courses.status', Status::ACTIVE->value)
                ->whereHas('school', function ($query) {
                    $query->where('schools.status', true);
                })
                ->whereHas('directions', function ($query) {
                    $query->where('directions.id', $this->data->id);
                })
                ->count();

            $templateValues = [
                'direction' => $this->data->name,
                'countDirectionCourses' => $countDirectionCourses,
            ];

            $template = new Template();

            $action = app(MetatagSetAction::class);
            $action->description = $template->convert($this->data->description_template, $templateValues);
            $action->title = $template->convert($this->data->title_template, $templateValues);
            $action->description_template = $this->data->description_template;
            $action->title_template = $this->data->title_template;
            $action->keywords = $this->data->keywords;
            $action->id = $directionEntity->metatag_id ?: null;

            $directionEntity->metatag_id = $action->run()->id;
            $directionEntity->id = $this->data->id;
            $directionEntity->name = Typography::process($this->data->name, true);
            $directionEntity->header = Typography::process($template->convert($this->data->header_template, $templateValues), true);
            $directionEntity->header_template = $this->data->header_template;
            $directionEntity->weight = $this->data->weight;
            $directionEntity->link = $this->data->link;
            $directionEntity->text = Typography::process($this->data->text);
            $directionEntity->status = $this->data->status;

            Direction::find($this->data->id)->update($directionEntity->toArray());

            Cache::tags(['catalog', 'category', 'direction', 'profession', 'teacher'])->flush();

            $action = new AnalyzerUpdateAction($directionEntity->id, Direction::class, 'direction.text');
            $action->run();

            $action = new DirectionGetAction($this->data->id);

            return $action->run();
        }

        throw new RecordNotExistException(
            trans('direction::actions.admin.directionUpdateAction.notExistDirection')
        );
    }
}
