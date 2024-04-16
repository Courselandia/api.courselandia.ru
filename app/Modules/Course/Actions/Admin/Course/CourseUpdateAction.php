<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Actions\Admin\Course;

use App\Modules\Course\Data\Actions\CourseUpdate;
use App\Modules\Metatag\Data\MetatagSet;
use Typography;
use App\Models\Action;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Analyzer\Actions\Admin\AnalyzerUpdateAction;
use App\Modules\Course\Entities\Course as CourseEntity;
use App\Modules\Course\Entities\CourseFeature as CourseFeatureEntity;
use App\Modules\Course\Entities\CourseLearn as CourseLearnEntity;
use App\Modules\Course\Entities\CourseLevel as CourseLevelEntity;
use App\Modules\Course\Models\Course;
use App\Modules\Course\Models\CourseFeature;
use App\Modules\Course\Models\CourseLearn;
use App\Modules\Course\Models\CourseLevel;
use App\Modules\Course\Normalize\Data;
use App\Modules\Metatag\Actions\MetatagSetAction;
use App\Modules\Metatag\Template\Template;
use App\Modules\Salary\Enums\Level;
use Cache;
use DB;
use Throwable;

/**
 * Класс действия для обновления курса.
 */
class CourseUpdateAction extends Action
{
    /**
     * Данные для действия обновления курса.
     *
     * @var CourseUpdate
     */
    private CourseUpdate $data;

    /**
     * @param CourseUpdate $data Данные для действия обновления курса.
     */
    public function __construct(CourseUpdate $data)
    {
        $this->data = $data;
    }

    /**
     * Метод запуска логики.
     *
     * @return CourseEntity Вернет результаты исполнения.
     * @throws RecordNotExistException
     * @throws Throwable
     */
    public function run(): CourseEntity
    {
        $action = new CourseGetAction($this->data->id);
        $courseEntity = $action->run();

        if ($courseEntity) {
            DB::transaction(function () use ($courseEntity) {
                $templateValues = [
                    'course' => $this->data->name,
                    'school' => $courseEntity->school->name,
                    'price' => $this->data->price,
                    'currency' => $this->data->currency,
                ];

                $template = new Template();

                $action = new MetatagSetAction(MetatagSet::from([
                    'description' => $template->convert($this->data->description_template, $templateValues),
                    'title' => $template->convert($this->data->title_template, $templateValues),
                    'description_template'=> $this->data->description_template,
                    'title_template' => $this->data->title_template,
                    'keywords' => $this->data->keywords,
                    'id' => $courseEntity->metatag_id ?: null,
                ]));

                $courseEntity = CourseEntity::from([
                    ...$courseEntity->toArray(),
                    ...$this->data->except(
                        'program',
                        'directions',
                        'professions',
                        'categories',
                        'skills',
                        'teachers',
                        'tools',
                        'processes',
                        'levels',
                        'learns',
                        'employments',
                        'features',
                    )->toArray(),
                    'metatag_id' => $action->run()->id,
                    'name' => Typography::process($this->data->name, true),
                    'header' => Typography::process($template->convert($this->data->header_template, $templateValues), true),
                    'text' => Typography::process($this->data->text),
                    'direction_ids' => Data::getDirections($this->data->directions ?: []),
                    'profession_ids' => Data::getProfessions($this->data->professions ?: []),
                    'category_ids' => Data::getCategories($this->data->categories ?: []),
                    'skill_ids' => Data::getSkills($this->data->skills ?: []),
                    'teacher_ids' => Data::getTeachers($this->data->teachers ?: []),
                    'tool_ids' => Data::getTools($this->data->tools ?: []),
                    'level_values' => Data::getLevels($this->data->levels ?: []),
                    'has_active_school' => Data::isActiveSchool($this->data->school_id),
                ]);

                $program = $this->data->program;

                if ($program) {
                    for ($i = 0; $i < count($program); $i++) {
                        $program[$i]['name'] = Typography::process($program[$i]['name'], true);
                        $program[$i]['text'] = Typography::process($program[$i]['text']);
                    }
                }

                $courseEntity->program = $program;
                $courseEntityData = $courseEntity->toArray();

                if ($this->data->image) {
                    $courseEntityData['image_small_id'] = $this->data->image;
                    $courseEntityData['image_middle_id'] = $this->data->image;
                    $courseEntityData['image_big_id'] = $this->data->image;
                }

                $course = Course::find($this->data->id);
                $course->update($courseEntityData);
                $course->directions()->sync($this->data->directions ?: []);
                $course->professions()->sync($this->data->professions ?: []);
                $course->categories()->sync($this->data->categories ?: []);
                $course->skills()->sync($this->data->skills ?: []);
                $course->teachers()->sync($this->data->teachers ?: []);
                $course->tools()->sync($this->data->tools ?: []);
                $course->processes()->sync($this->data->processes ?: []);
                $course->employments()->sync($this->data->employments ?: []);

                CourseLevel::whereIn('id', collect($courseEntity->levels)->pluck('id')->toArray())
                    ->forceDelete();

                if ($this->data->levels) {
                    foreach ($this->data->levels as $level) {
                        $entity = new CourseLevelEntity();
                        $entity->course_id = $this->data->id;
                        $entity->level = Level::from($level);

                        CourseLevel::create($entity->toArray());
                    }
                }

                CourseLearn::whereIn('id', collect($courseEntity->learns)->pluck('id')->toArray())
                    ->forceDelete();

                if ($this->data->learns) {
                    foreach ($this->data->learns as $learn) {
                        $entity = new CourseLearnEntity();
                        $entity->course_id = $this->data->id;
                        $entity->text = Typography::process($learn, true);

                        CourseLearn::create($entity->toArray());
                    }
                }

                CourseFeature::whereIn('id', collect($courseEntity->features)->pluck('id')->toArray())
                    ->forceDelete();

                if ($this->data->features) {
                    foreach ($this->data->features as $feature) {
                        $entity = new CourseFeatureEntity();
                        $entity->course_id = $this->data->id;
                        $entity->text = Typography::process($feature['text'], true);
                        $entity->icon = $feature['icon'];

                        CourseFeature::create($entity->toArray());
                    }
                }
            });

            $action = new AnalyzerUpdateAction($courseEntity->id, Course::class, 'course.text');

            $action->run();

            Cache::tags(['catalog', 'course'])->flush();

            $action = new CourseGetAction($this->data->id);

            return $action->run();
        }

        throw new RecordNotExistException(
            trans('course::actions.admin.courseUpdateAction.notExistCourse')
        );
    }
}
