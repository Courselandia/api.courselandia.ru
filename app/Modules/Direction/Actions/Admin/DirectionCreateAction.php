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
use App\Modules\Direction\Entities\Direction as DirectionEntity;
use App\Modules\Direction\Repositories\Direction;
use App\Modules\Metatag\Actions\MetatagSetAction;
use Cache;
use ReflectionException;

/**
 * Класс действия для создания направления.
 */
class DirectionCreateAction extends Action
{
    /**
     * Репозиторий направлений.
     *
     * @var Direction
     */
    private Direction $direction;

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
     * @throws ReflectionException
     */
    public function run(): DirectionEntity
    {
        $action = app(MetatagSetAction::class);
        $action->description = $this->description;
        $action->keywords = $this->keywords;
        $action->title = $this->title;
        $metatag = $action->run();

        $directionEntity = new DirectionEntity();
        $directionEntity->name = $this->name;
        $directionEntity->header = $this->header;
        $directionEntity->weight = $this->weight;
        $directionEntity->link = $this->link;
        $directionEntity->text = $this->text;
        $directionEntity->status = $this->status;
        $directionEntity->metatag_id = $metatag->id;

        $id = $this->direction->create($directionEntity);
        Cache::tags(['catalog', 'category', 'direction', 'profession'])->flush();

        $action = app(DirectionGetAction::class);
        $action->id = $id;

        return $action->run();
    }
}
