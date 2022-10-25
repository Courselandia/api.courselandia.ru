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
use App\Modules\Metatag\Actions\MetatagSetAction;
use Cache;
use ReflectionException;

/**
 * Класс действия для создания профессии.
 */
class ProfessionCreateAction extends Action
{
    /**
     * Репозиторий профессий.
     *
     * @var Profession
     */
    private Profession $profession;

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
        $action = app(MetatagSetAction::class);
        $action->description = $this->description;
        $action->keywords = $this->keywords;
        $action->title = $this->title;
        $metatag = $action->run();

        $professionEntity = new ProfessionEntity();
        $professionEntity->name = $this->name;
        $professionEntity->header = $this->header;
        $professionEntity->link = $this->link;
        $professionEntity->text = $this->text;
        $professionEntity->status = $this->status;
        $professionEntity->metatag_id = $metatag->id;

        $id = $this->profession->create($professionEntity);
        Cache::tags(['profession'])->flush();

        $action = app(ProfessionGetAction::class);
        $action->id = $id;

        return $action->run();
    }
}
