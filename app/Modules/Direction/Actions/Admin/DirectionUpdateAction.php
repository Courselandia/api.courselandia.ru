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
use App\Modules\Image\Entities\Image;
use App\Modules\Metatag\Actions\MetatagSetAction;
use Cache;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use ReflectionException;

/**
 * Класс действия для обновления направлений.
 */
class DirectionUpdateAction extends Action
{
    /**
     * Репозиторий направлений.
     *
     * @var Direction
     */
    private Direction $direction;

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
        $action = app(DirectionGetAction::class);
        $action->id = $this->id;
        $directionEntity = $action->run();

        if ($directionEntity) {
            $action = app(MetatagSetAction::class);
            $action->description = $this->description;
            $action->keywords = $this->keywords;
            $action->title = $this->title;
            $metatag = $action->run();

            $directionEntity->id = $this->id;
            $directionEntity->metatag_id = $metatag->id;
            $directionEntity->name = $this->name;
            $directionEntity->header = $this->header;
            $directionEntity->weight = $this->weight;
            $directionEntity->link = $this->link;
            $directionEntity->text = $this->text;
            $directionEntity->status = $this->status;

            $this->direction->update($this->id, $directionEntity);
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
