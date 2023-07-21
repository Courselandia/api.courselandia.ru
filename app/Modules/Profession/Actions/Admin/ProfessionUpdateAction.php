<?php
/**
 * Модуль Профессии.
 * Этот модуль содержит все классы для работы с профессиями.
 *
 * @package App\Modules\Profession
 */

namespace App\Modules\Profession\Actions\Admin;

use Typography;
use App\Models\Action;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Course\Enums\Status;
use App\Modules\Course\Models\Course;
use App\Modules\Metatag\Template\Template;
use App\Modules\Metatag\Template\TemplateException;
use App\Modules\Profession\Entities\Profession as ProfessionEntity;
use App\Modules\Profession\Models\Profession;
use App\Modules\Metatag\Actions\MetatagSetAction;
use Cache;

/**
 * Класс действия для обновления профессий.
 */
class ProfessionUpdateAction extends Action
{
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
     * Шаблон заголовка.
     *
     * @var string|null
     */
    public ?string $header_template = null;

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
     * @return ProfessionEntity Вернет результаты исполнения.
     * @throws RecordNotExistException
     * @throws ParameterInvalidException
     * @throws TemplateException
     */
    public function run(): ProfessionEntity
    {
        $action = app(ProfessionGetAction::class);
        $action->id = $this->id;
        $professionEntity = $action->run();

        if ($professionEntity) {
            $countProfessionCourses = Course::where('courses.status', Status::ACTIVE->value)
                ->whereHas('school', function ($query) {
                    $query->where('schools.status', true);
                })
                ->whereHas('professions', function ($query) {
                    $query->where('professions.id', $this->id);
                })
                ->count();

            $templateValues = [
                'profession' => $this->name,
                'countProfessionCourses' => $countProfessionCourses,
            ];

            $template = new Template();

            $action = app(MetatagSetAction::class);
            $action->description = $template->convert($this->description_template, $templateValues);
            $action->title = $template->convert($this->title_template, $templateValues);
            $action->description_template = $this->description_template;
            $action->title_template = $this->title_template;
            $action->keywords = $this->keywords;
            $action->id = $professionEntity->metatag_id ?: null;

            $professionEntity->metatag_id = $action->run()->id;
            $professionEntity->id = $this->id;
            $professionEntity->name = Typography::process($this->name, true);
            $professionEntity->header = Typography::process($template->convert($this->header_template, $templateValues), true);
            $professionEntity->header_template = $this->header_template;
            $professionEntity->link = $this->link;
            $professionEntity->text = Typography::process($this->text);
            $professionEntity->status = $this->status;

            Profession::find($this->id)->update($professionEntity->toArray());
            Cache::tags(['catalog', 'category', 'direction', 'salary', 'profession'])->flush();

            $action = app(ProfessionGetAction::class);
            $action->id = $this->id;

            return $action->run();
        }

        throw new RecordNotExistException(
            trans('profession::actions.admin.professionUpdateAction.notExistProfession')
        );
    }
}
