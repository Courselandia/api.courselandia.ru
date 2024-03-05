<?php
/**
 * Модуль Школ.
 * Этот модуль содержит все классы для работы со школами.
 *
 * @package App\Modules\School
 */

namespace App\Modules\School\Actions\Admin\School;

use DB;
use App\Modules\Analyzer\Actions\Admin\AnalyzerUpdateAction;
use App\Modules\Metatag\Data\MetatagSet;
use App\Modules\School\Data\SchoolCreate;
use Cache;
use Throwable;
use Typography;
use App\Models\Action;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Metatag\Actions\MetatagSetAction;
use App\Modules\Metatag\Template\Template;
use App\Modules\Metatag\Template\TemplateException;
use App\Modules\School\Entities\School as SchoolEntity;
use App\Modules\School\Models\School;

/**
 * Класс действия для создания школы.
 */
class SchoolCreateAction extends Action
{
    /**
     * Данные для создания школы.
     *
     * @var SchoolCreate
     */
    private SchoolCreate $data;

    /**
     * @param SchoolCreate $data Данные для создания школы.
     */
    public function __construct(SchoolCreate $data)
    {
        $this->data = $data;
    }

    /**
     * Метод запуска логики.
     *
     * @return SchoolEntity Вернет результаты исполнения.
     * @throws ParameterInvalidException
     * @throws TemplateException
     * @throws Throwable
     */
    public function run(): SchoolEntity
    {
        $id = DB::transaction(function () {
            $template = new Template();

            $templateValues = [
                'school' => $this->data->name,
                'countSchoolCourses' => 0,
            ];

            $action = new MetatagSetAction(MetatagSet::from([
                'description' => $template->convert($this->data->description_template, $templateValues),
                'title' => $template->convert($this->data->title_template, $templateValues),
                'description_template' => $this->data->description_template,
                'title_template' => $this->data->title_template,
                'keywords' => $this->data->keywords
            ]));

            $metatag = $action->run();

            $schoolEntity = SchoolEntity::from([
                ...$this->data->except('image_logo_id', 'image_site_id')->toArray(),
                'name' => Typography::process($this->data->name, true),
                'header' => Typography::process($template->convert($this->data->header_template, $templateValues), true),
                'text' => Typography::process($this->data->text),
                'additional' => Typography::process($this->data->additional),
                'metatag_id' => $metatag->id,
            ]);

            $school = School::create([
                ...$schoolEntity->toArray(),
                'image_logo_id' => $this->data->image_logo_id,
                'image_site_id' => $this->data->image_site_id,
            ]);
            Cache::tags(['catalog', 'school'])->flush();

            $action = new AnalyzerUpdateAction($school->id, School::class, 'school.text');
            $action->run();

            return $school->id;
        });

        $action = new SchoolGetAction($id);

        return $action->run();
    }
}
