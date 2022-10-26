<?php
/**
 * Модуль Навыков.
 * Этот модуль содержит все классы для работы с навыками.
 *
 * @package App\Modules\Skill
 */

namespace App\Modules\Skill\Actions\Admin;

use App\Models\Action;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Skill\Entities\Skill as SkillEntity;
use App\Modules\Skill\Repositories\Skill;
use App\Modules\Metatag\Actions\MetatagSetAction;
use Cache;
use ReflectionException;

/**
 * Класс действия для создания навыка.
 */
class SkillCreateAction extends Action
{
    /**
     * Репозиторий навыков.
     *
     * @var Skill
     */
    private Skill $skill;

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
     * @param  Skill  $skill  Репозиторий навыков.
     */
    public function __construct(Skill $skill)
    {
        $this->skill = $skill;
    }

    /**
     * Метод запуска логики.
     *
     * @return SkillEntity Вернет результаты исполнения.
     * @throws RecordNotExistException
     * @throws ParameterInvalidException
     * @throws ReflectionException
     */
    public function run(): SkillEntity
    {
        $action = app(MetatagSetAction::class);
        $action->description = $this->description;
        $action->keywords = $this->keywords;
        $action->title = $this->title;
        $metatag = $action->run();

        $skillEntity = new SkillEntity();
        $skillEntity->name = $this->name;
        $skillEntity->header = $this->header;
        $skillEntity->link = $this->link;
        $skillEntity->text = $this->text;
        $skillEntity->status = $this->status;
        $skillEntity->metatag_id = $metatag->id;

        $id = $this->skill->create($skillEntity);
        Cache::tags(['catalog', 'skill'])->flush();

        $action = app(SkillGetAction::class);
        $action->id = $id;

        return $action->run();
    }
}
