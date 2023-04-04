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
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Course\Enums\Status;
use App\Modules\Course\Models\Course;
use App\Modules\Direction\Entities\Direction as DirectionEntity;
use App\Modules\Direction\Models\Direction;
use App\Modules\Metatag\Actions\MetatagSetAction;
use App\Modules\Metatag\Template\Template;
use App\Modules\Metatag\Template\TemplateException;
use Cache;

/**
 * Класс действия для обновления направлений.
 */
class DirectionUpdateAction extends Action
{
    /**
     * ID направления.
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
     * Вес.
     *
     * @var int|null
     */
    public ?int $weight = null;

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
     * Конструктор.
     *
     * @param  Direction  $direction  Репозиторий направлений.
     */
    public function __construct(Direction $direction)
    {
        $this->direction = $direction;
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
        $action = app(DirectionGetAction::class);
        $action->id = $this->id;
        $directionEntity = $action->run();

        if ($directionEntity) {
            $countDirectionCourses = Course::where('courses.status', Status::ACTIVE->value)
                ->whereHas('school', function ($query) {
                    $query->where('schools.status', true);
                })
                ->whereHas('directions', function ($query) {
                    $query->where('directions.id', $this->id);
                })
                ->count();

            $templateValues = [
                'direction' => $this->name,
                'countDirectionCourses' => $countDirectionCourses,
            ];

            $template = new Template();

            $action = app(MetatagSetAction::class);
            $action->description = $template->convert($this->description_template, $templateValues);
            $action->title = $template->convert($this->title_template, $templateValues);
            $action->description_template = $this->description_template;
            $action->title_template = $this->title_template;
            $action->keywords = $this->keywords;
            $action->id = $directionEntity->metatag_id ?: null;

            $directionEntity->metatag_id = $action->run()->id;
            $directionEntity->id = $this->id;
            $directionEntity->name = $this->name;
            $directionEntity->header = $template->convert($this->header_template, $templateValues);
            $directionEntity->header_template = $this->header_template;
            $directionEntity->weight = $this->weight;
            $directionEntity->link = $this->link;
            $directionEntity->text = $this->text;
            $directionEntity->status = $this->status;

            Direction::find($this->id)->update($directionEntity->toArray());

            Cache::tags(['catalog', 'category', 'direction', 'profession', 'teacher'])->flush();

            $action = app(DirectionGetAction::class);
            $action->id = $this->id;

            return $action->run();
        }

        throw new RecordNotExistException(
            trans('direction::actions.admin.directionUpdateAction.notExistDirection')
        );
    }
}
