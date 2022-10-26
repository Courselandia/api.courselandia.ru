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
use App\Modules\Image\Entities\Image;
use App\Modules\Metatag\Actions\MetatagSetAction;
use Cache;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use ReflectionException;

/**
 * Класс действия для обновления инструментов.
 */
class ToolUpdateAction extends Action
{
    /**
     * Репозиторий инструментов.
     *
     * @var Tool
     */
    private Tool $tool;

    /**
     * ID инструмента.
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
        $action = app(ToolGetAction::class);
        $action->id = $this->id;
        $toolEntity = $action->run();

        if ($toolEntity) {
            $action = app(MetatagSetAction::class);
            $action->description = $this->description;
            $action->keywords = $this->keywords;
            $action->title = $this->title;
            $metatag = $action->run();

            $toolEntity->id = $this->id;
            $toolEntity->metatag_id = $metatag->id;
            $toolEntity->name = $this->name;
            $toolEntity->header = $this->header;
            $toolEntity->link = $this->link;
            $toolEntity->text = $this->text;
            $toolEntity->status = $this->status;

            $this->tool->update($this->id, $toolEntity);
            Cache::tags(['catalog', 'tool'])->flush();

            $action = app(ToolGetAction::class);
            $action->id = $this->id;

            return $action->run();
        }

        throw new RecordNotExistException(
            trans('tool::actions.admin.toolUpdateAction.notExistTool')
        );
    }
}
