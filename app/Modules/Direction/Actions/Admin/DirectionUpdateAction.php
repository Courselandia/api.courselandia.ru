<?php
/**
 * Модуль Направления.
 * Этот модуль содержит все классы для работы с направлениями.
 *
 * @package App\Modules\Direction
 */

namespace App\Modules\Direction\Actions\Admin;

use DB;
use App\Modules\Analyzer\Actions\Admin\AnalyzerUpdateAction;
use App\Modules\Direction\Data\DirectionUpdate;
use App\Modules\Metatag\Data\MetatagSet;
use Cache;
use Throwable;
use Typography;
use App\Models\Action;
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
     * @throws TemplateException
     * @throws Throwable
     */
    public function run(): DirectionEntity
    {
        $action = new DirectionGetAction($this->data->id);
        $directionEntity = $action->run();

        if ($directionEntity) {
            DB::transaction(function () use ($directionEntity) {
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

                $metatagSet = MetatagSet::from([
                    'description' => $template->convert($this->data->description_template, $templateValues),
                    'title' => $template->convert($this->data->title_template, $templateValues),
                    'description_template' => $this->data->description_template,
                    'title_template' => $this->data->title_template,
                    'keywords' => $this->data->keywords,
                    'id' => $directionEntity->metatag_id ?: null,
                ]);

                $action = new MetatagSetAction($metatagSet);

                $directionEntity = DirectionEntity::from([
                    ...$directionEntity->toArray(),
                    ...$this->data->toArray(),
                    'name' => Typography::process($this->data->name, true),
                    'header' => Typography::process($template->convert($this->data->header_template, $templateValues), true),
                    'text' => Typography::process($this->data->text),
                    'additional' => Typography::process($this->data->additional),
                    'metatag_id' => $action->run()->id
                ]);

                Direction::find($this->data->id)->update($directionEntity->toArray());

                Cache::tags(['catalog', 'direction'])->flush();

                $action = new AnalyzerUpdateAction($directionEntity->id, Direction::class, 'direction.text');
                $action->run();
            });

            $action = new DirectionGetAction($this->data->id);

            return $action->run();
        }

        throw new RecordNotExistException(
            trans('direction::actions.admin.directionUpdateAction.notExistDirection')
        );
    }
}
