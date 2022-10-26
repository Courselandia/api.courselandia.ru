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
use App\Modules\Image\Entities\Image;
use App\Modules\Metatag\Actions\MetatagSetAction;
use Cache;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use ReflectionException;

/**
 * Класс действия для обновления навыков.
 */
class SkillUpdateAction extends Action
{
    /**
     * Репозиторий навыков.
     *
     * @var Skill
     */
    private Skill $skill;

    /**
     * ID навыка.
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
        $action = app(SkillGetAction::class);
        $action->id = $this->id;
        $skillEntity = $action->run();

        if ($skillEntity) {
            $action = app(MetatagSetAction::class);
            $action->description = $this->description;
            $action->keywords = $this->keywords;
            $action->title = $this->title;
            $metatag = $action->run();

            $skillEntity->id = $this->id;
            $skillEntity->metatag_id = $metatag->id;
            $skillEntity->name = $this->name;
            $skillEntity->header = $this->header;
            $skillEntity->link = $this->link;
            $skillEntity->text = $this->text;
            $skillEntity->status = $this->status;

            $this->skill->update($this->id, $skillEntity);
            Cache::tags(['catalog', 'skill'])->flush();

            $action = app(SkillGetAction::class);
            $action->id = $this->id;

            return $action->run();
        }

        throw new RecordNotExistException(
            trans('skill::actions.admin.skillUpdateAction.notExistSkill')
        );
    }
}
