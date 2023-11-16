<?php
/**
 * Модуль Направления.
 * Этот модуль содержит все классы для работы с направлениями.
 *
 * @package App\Modules\Direction
 */

namespace App\Modules\Direction\Actions\Admin;

use Cache;
use Typography;
use App\Models\Action;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Direction\Entities\Direction as DirectionEntity;
use App\Modules\Direction\Models\Direction;
use App\Modules\Metatag\Actions\MetatagSetAction;
use App\Modules\Metatag\Template\Template;
use App\Modules\Metatag\Template\TemplateException;
use App\Modules\Analyzer\Actions\Admin\AnalyzerUpdateAction;

/**
 * Класс действия для создания направления.
 */
class DirectionCreateAction extends Action
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
     * Метод запуска логики.
     *
     * @return DirectionEntity Вернет результаты исполнения.
     * @throws ParameterInvalidException|TemplateException
     */
    public function run(): DirectionEntity
    {
        $action = app(MetatagSetAction::class);
        $template = new Template();

        $templateValues = [
            'direction' => $this->name,
            'countDirectionCourses' => 0,
        ];

        $action->description = $template->convert($this->description_template, $templateValues);
        $action->title = $template->convert($this->title_template, $templateValues);
        $action->description_template = $this->description_template;
        $action->title_template = $this->title_template;
        $action->keywords = $this->keywords;

        $metatag = $action->run();

        $directionEntity = new DirectionEntity();
        $directionEntity->name = Typography::process($this->name, true);
        $directionEntity->header = Typography::process($template->convert($this->header_template, $templateValues), true);
        $directionEntity->header_template = $this->header_template;
        $directionEntity->weight = $this->weight;
        $directionEntity->link = $this->link;
        $directionEntity->text = Typography::process($this->text);
        $directionEntity->status = $this->status;
        $directionEntity->metatag_id = $metatag->id;

        $direction = Direction::create($directionEntity->toArray());

        Cache::tags(['catalog', 'category', 'direction', 'profession', 'teacher'])->flush();

        $action = app(AnalyzerUpdateAction::class);
        $action->id = $direction->id;
        $action->model = Direction::class;
        $action->category = 'direction.text';
        $action->run();

        $action = app(DirectionGetAction::class);
        $action->id = $direction->id;

        return $action->run();
    }
}
