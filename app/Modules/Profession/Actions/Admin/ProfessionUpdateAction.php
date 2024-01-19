<?php
/**
 * Модуль Профессии.
 * Этот модуль содержит все классы для работы с профессиями.
 *
 * @package App\Modules\Profession
 */

namespace App\Modules\Profession\Actions\Admin;

use App\Modules\Analyzer\Actions\Admin\AnalyzerUpdateAction;
use App\Modules\Profession\Data\ProfessionUpdate;
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
     * @var ProfessionUpdate Данные для обновления профессии.
     */
    private ProfessionUpdate $data;

    /**
     * @param ProfessionUpdate $data Данные для обновления профессии.
     */
    public function __construct(ProfessionUpdate $data)
    {
        $this->data = $data;
    }

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
        $action = new ProfessionGetAction($this->data->id);
        $professionEntity = $action->run();

        if ($professionEntity) {
            $countProfessionCourses = Course::where('courses.status', Status::ACTIVE->value)
                ->whereHas('school', function ($query) {
                    $query->where('schools.status', true);
                })
                ->whereHas('professions', function ($query) {
                    $query->where('professions.id', $this->data->id);
                })
                ->count();

            $templateValues = [
                'profession' => $this->data->name,
                'countProfessionCourses' => $countProfessionCourses,
            ];

            $template = new Template();

            $action = app(MetatagSetAction::class);
            $action->description = $template->convert($this->data->description_template, $templateValues);
            $action->title = $template->convert($this->data->title_template, $templateValues);
            $action->description_template = $this->data->description_template;
            $action->title_template = $this->data->title_template;
            $action->keywords = $this->data->keywords;
            $action->id = $professionEntity->metatag_id ?: null;

            $professionEntity->metatag_id = $action->run()->id;
            $professionEntity->id = $this->data->id;
            $professionEntity->name = Typography::process($this->data->name, true);
            $professionEntity->header = Typography::process($template->convert($this->data->header_template, $templateValues), true);
            $professionEntity->header_template = $this->data->header_template;
            $professionEntity->link = $this->data->link;
            $professionEntity->text = Typography::process($this->data->text);
            $professionEntity->status = $this->data->status;

            Profession::find($this->data->id)->update($professionEntity->toArray());
            Cache::tags(['catalog', 'category', 'direction', 'salary', 'profession'])->flush();

            $action = new AnalyzerUpdateAction($professionEntity->id, Profession::class, 'profession.text');
            $action->run();

            $action = new ProfessionGetAction($this->data->id);

            return $action->run();
        }

        throw new RecordNotExistException(
            trans('profession::actions.admin.professionUpdateAction.notExistProfession')
        );
    }
}
