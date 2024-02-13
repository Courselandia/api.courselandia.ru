<?php
/**
 * Модуль Школ.
 * Этот модуль содержит все классы для работы со школами.
 *
 * @package App\Modules\School
 */

namespace App\Modules\School\Actions\Admin\School;

use App\Modules\Analyzer\Actions\Admin\AnalyzerUpdateAction;
use App\Modules\Metatag\Data\MetatagSet;
use App\Modules\School\Data\SchoolUpdate;
use Cache;
use Typography;
use App\Models\Action;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Course\Enums\Status;
use App\Modules\Course\Models\Course;
use App\Modules\Metatag\Actions\MetatagSetAction;
use App\Modules\Metatag\Template\Template;
use App\Modules\Metatag\Template\TemplateException;
use App\Modules\School\Entities\School as SchoolEntity;
use App\Modules\School\Models\School;

/**
 * Класс действия для обновления школ.
 */
class SchoolUpdateAction extends Action
{
    /**
     * @var SchoolUpdate Данные для обновления школы.
     */
    private SchoolUpdate $data;

    /**
     * @param SchoolUpdate $data Данные для обновления школы.
     */
    public function __construct(SchoolUpdate $data)
    {
        $this->data = $data;
    }

    /**
     * Метод запуска логики.
     *
     * @return SchoolEntity Вернет результаты исполнения.
     * @throws RecordNotExistException
     * @throws ParameterInvalidException
     * @throws TemplateException
     */
    public function run(): SchoolEntity
    {
        $action = new SchoolGetAction($this->data->id);
        $schoolEntity = $action->run();

        if ($schoolEntity) {
            $countSchoolCourses = Course::where('courses.status', Status::ACTIVE->value)
                ->whereHas('school', function ($query) {
                    $query->where('schools.status', true)
                        ->where('schools.id', $this->data->id);
                })
                ->count();

            $templateValues = [
                'school' => $this->data->name,
                'countSchoolCourses' => $countSchoolCourses,
            ];

            $template = new Template();

            $action = new MetatagSetAction(MetatagSet::from([
                'description' => $template->convert($this->data->description_template, $templateValues),
                'title' => $template->convert($this->data->title_template, $templateValues),
                'description_template' => $this->data->description_template,
                'title_template' => $this->data->title_template,
                'keywords' => $this->data->keywords,
                'id' => $schoolEntity->metatag_id ?: null,
            ]));

            $schoolEntity = SchoolEntity::from([
                ...$schoolEntity->toArray(),
                ...$this->data->except('image_logo_id', 'image_site_id')->toArray(),
                'metatag_id' => $action->run()->id,
                'name' => Typography::process($this->data->name, true),
                'header' => Typography::process($template->convert($this->data->header_template, $templateValues), true),
                'text' => Typography::process($this->data->text),
            ]);

            $data = $schoolEntity->toArray();

            if ($this->data->image_logo_id) {
                $data['image_logo_id'] = $this->data->image_logo_id;
            }

            if ($this->data->image_site_id) {
                $data['image_site_id'] = $this->data->image_site_id;
            }

            School::find($this->data->id)->update($data);
            Cache::tags(['catalog', 'school', 'teacher', 'review', 'faq'])->flush();

            $action = new AnalyzerUpdateAction($schoolEntity->id, School::class, 'school.text');
            $action->run();

            $action = new SchoolGetAction($this->data->id);

            return $action->run();
        }

        throw new RecordNotExistException(
            trans('school::actions.admin.schoolUpdateAction.notExistSchool')
        );
    }
}
