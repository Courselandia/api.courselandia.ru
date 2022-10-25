<?php
/**
 * Модуль Профессии.
 * Этот модуль содержит все классы для работы с профессиями.
 *
 * @package App\Modules\Profession
 */

namespace App\Modules\Profession\Actions\Admin;

use App\Models\Action;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Profession\Entities\Profession as ProfessionEntity;
use App\Modules\Profession\Repositories\Profession;
use App\Modules\Image\Entities\Image;
use App\Modules\Metatag\Actions\MetatagSetAction;
use Cache;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use ReflectionException;

/**
 * Класс действия для обновления профессий.
 */
class ProfessionUpdateAction extends Action
{
    /**
     * Репозиторий профессий.
     *
     * @var Profession
     */
    private Profession $profession;

    /**
     * ID профессии.
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
     * @param  Profession  $profession  Репозиторий профессий.
     */
    public function __construct(Profession $profession)
    {
        $this->profession = $profession;
    }

    /**
     * Метод запуска логики.
     *
     * @return ProfessionEntity Вернет результаты исполнения.
     * @throws RecordNotExistException
     * @throws ParameterInvalidException
     * @throws ReflectionException
     */
    public function run(): ProfessionEntity
    {
        $action = app(ProfessionGetAction::class);
        $action->id = $this->id;
        $professionEntity = $action->run();

        if ($professionEntity) {
            $action = app(MetatagSetAction::class);
            $action->description = $this->description;
            $action->keywords = $this->keywords;
            $action->title = $this->title;
            $metatag = $action->run();

            $professionEntity->id = $this->id;
            $professionEntity->metatag_id = $metatag->id;
            $professionEntity->name = $this->name;
            $professionEntity->header = $this->header;
            $professionEntity->link = $this->link;
            $professionEntity->text = $this->text;
            $professionEntity->status = $this->status;

            $this->profession->update($this->id, $professionEntity);
            Cache::tags(['catalog', 'category', 'direction', 'profession'])->flush();

            $action = app(ProfessionGetAction::class);
            $action->id = $this->id;

            return $action->run();
        }

        throw new RecordNotExistException(
            trans('profession::actions.admin.professionUpdateAction.notExistProfession')
        );
    }
}
