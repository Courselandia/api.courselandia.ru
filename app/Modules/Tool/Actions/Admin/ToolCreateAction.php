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
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Tool\Entities\Tool as ToolEntity;
use App\Modules\Tool\Repositories\Tool;
use App\Modules\Metatag\Actions\MetatagSetAction;
use Cache;
use ReflectionException;

/**
 * Класс действия для создания инструмента.
 */
class ToolCreateAction extends Action
{
    /**
     * Репозиторий инструментов.
     *
     * @var Tool
     */
    private Tool $tool;

    /**
     * Название.
     *
     * @var string|null
     */
    public ?string $name = null;

    /**
     * Заголовок.
     *
     * @var string|null
     */
    public ?string $header = null;

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
     * Описание.
     *
     * @var string|null
     */
    public ?string $description = null;

    /**
     * Ключевые слова.
     *
     * @var string|null
     */
    public ?string $keywords = null;

    /**
     * Заголовок.
     *
     * @var string|null
     */
    public ?string $title = null;

    /**
     * Конструктор.
     *
     * @param  Tool  $tool  Репозиторий инструментов.
     */
    public function __construct(Tool $tool)
    {
        $this->tool = $tool;
    }

    /**
     * Метод запуска логики.
     *
     * @return ToolEntity Вернет результаты исполнения.
     * @throws RecordNotExistException
     * @throws ParameterInvalidException
     * @throws ReflectionException
     */
    public function run(): ToolEntity
    {
        $action = app(MetatagSetAction::class);
        $action->description = $this->description;
        $action->keywords = $this->keywords;
        $action->title = $this->title;
        $metatag = $action->run();

        $toolEntity = new ToolEntity();
        $toolEntity->name = $this->name;
        $toolEntity->header = $this->header;
        $toolEntity->link = $this->link;
        $toolEntity->text = $this->text;
        $toolEntity->status = $this->status;
        $toolEntity->metatag_id = $metatag->id;

        $id = $this->tool->create($toolEntity);
        Cache::tags(['catalog', 'tool'])->flush();

        $action = app(ToolGetAction::class);
        $action->id = $id;

        return $action->run();
    }
}
