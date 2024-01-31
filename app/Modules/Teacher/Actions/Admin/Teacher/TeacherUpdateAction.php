<?php
/**
 * Модуль Учителей.
 * Этот модуль содержит все классы для работы с учителями.
 *
 * @package App\Modules\Teacher
 */

namespace App\Modules\Teacher\Actions\Admin\Teacher;

use App\Modules\Analyzer\Actions\Admin\AnalyzerUpdateAction;
use App\Modules\Metatag\Data\MetatagSet;
use App\Modules\Teacher\Data\TeacherUpdate;
use Carbon\Carbon;
use DB;
use Cache;
use ImageStore;
use Throwable;
use Typography;
use App\Models\Action;
use App\Modules\Teacher\Enums\SocialMedia;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Course\Enums\Status;
use App\Modules\Course\Models\Course;
use App\Modules\Metatag\Actions\MetatagSetAction;
use App\Modules\Metatag\Template\Template;
use App\Modules\Metatag\Template\TemplateException;
use App\Modules\Teacher\Entities\Teacher as TeacherEntity;
use App\Modules\Teacher\Models\Teacher;
use Illuminate\Http\UploadedFile;
use App\Modules\Teacher\Entities\TeacherExperience as TeacherExperienceEntity;
use App\Modules\Teacher\Models\TeacherExperience;
use App\Modules\Teacher\Models\TeacherSocialMedia;
use App\Modules\Teacher\Entities\TeacherSocialMedia as TeacherSocialMediaEntity;

/**
 * Класс действия для обновления учителя.
 */
class TeacherUpdateAction extends Action
{
    /**
     * @var TeacherUpdate Данные для обновления учителя.
     */
    private TeacherUpdate $data;

    /**
     * @param TeacherUpdate $data Данные для обновления учителя.
     */
    public function __construct(TeacherUpdate $data)
    {
        $this->data = $data;
    }

    /**
     * Метод запуска логики.
     *
     * @return TeacherEntity Вернет результаты исполнения.
     * @throws RecordNotExistException
     * @throws ParameterInvalidException
     * @throws TemplateException|Throwable
     */
    public function run(): TeacherEntity
    {
        $action = new TeacherGetAction($this->data->id);
        $teacherEntity = $action->run();

        if ($teacherEntity) {
            DB::transaction(function () use ($teacherEntity) {
                $countTeacherCourses = Course::where('courses.status', Status::ACTIVE->value)
                    ->whereHas('school', function ($query) {
                        $query->where('schools.status', true);
                    })
                    ->whereHas('teachers', function ($query) {
                        $query->where('teachers.id', $this->data->id);
                    })
                    ->count();

                $templateValues = [
                    'teacher' => $this->data->name,
                    'countTeacherCourses' => $countTeacherCourses,
                ];

                $template = new Template();

                $action = new MetatagSetAction(MetatagSet::from([
                    'description' => $template->convert($this->data->description_template, $templateValues),
                    'title' => $template->convert($this->data->title_template, $templateValues),
                    'description_template' => $this->data->description_template,
                    'title_template' => $this->data->title_template,
                    'keywords' => $this->data->keywords,
                    'id' => $teacherEntity->metatag_id ?: null,
                ]));

                $teacherEntity = TeacherEntity::from([
                    ...$teacherEntity->toArray(),
                    ...$this->data
                        ->except('directions', 'schools', 'experiences', 'socialMedias')
                        ->toArray(),
                    'metatag_id' => $action->run()->id,
                    'name' => Typography::process($this->data->name, true),
                    'text' => Typography::process($this->data->text),
                ]);

                $teacherEntity = $teacherEntity->toArray();

                if ($this->data->image) {
                    $teacherEntity['image_big_id'] = $this->data->image;
                    $teacherEntity['image_small_id'] = $this->data->image;
                }

                if ($this->data->imageCropped) {
                    list(, $data) = explode(';', $this->data->imageCropped);
                    list(, $data) = explode(',', $data);
                    $data = base64_decode($data);
                    $imageName = ImageStore::tmp('webp');
                    file_put_contents($imageName, $data);

                    $teacherEntity['image_middle_id'] = new UploadedFile($imageName, basename($imageName), 'image/webp');
                    $teacherEntity['image_cropped_options'] = $this->data->imageCroppedOptions;
                }

                $teacher = Teacher::find($this->data->id);
                $teacher->update($teacherEntity);
                $teacher->directions()->sync($this->data->directions ?: []);
                $teacher->schools()->sync($this->data->schools ?: []);

                TeacherExperience::whereIn('id', collect($teacherEntity['experiences'])->pluck('id')->toArray())
                    ->forceDelete();

                if ($this->data->experiences) {
                    foreach ($this->data->experiences as $experience) {
                        /**
                         * @var TeacherExperience $experience
                         */
                        $entity = TeacherExperienceEntity::from([
                            ...$experience->toArray(),
                            'teacher_id' => $teacher->id,
                        ]);

                        TeacherExperience::create($entity->toArray());
                    }
                }

                TeacherSocialMedia::whereIn('id', collect($teacherEntity['social_medias'])->pluck('id')->toArray())
                    ->forceDelete();

                if ($this->data->socialMedias) {
                    foreach ($this->data->socialMedias as $socialMedia) {
                        /**
                         * @var TeacherSocialMedia $socialMedia
                         */
                        $entity = new TeacherSocialMediaEntity();
                        $entity->teacher_id = $teacher->id;
                        $entity->name = SocialMedia::from($socialMedia->name);
                        $entity->value = $socialMedia->value;

                        TeacherSocialMedia::create($entity->toArray());
                    }
                }
            });

            Cache::tags(['catalog', 'teacher', 'direction', 'school'])->flush();

            if (!$this->data->copied) {
                $action = new AnalyzerUpdateAction($this->data->id, Teacher::class, 'teacher.text');
                $action->run();
            }

            $action =  new TeacherGetAction($this->data->id);

            return $action->run();
        }

        throw new RecordNotExistException(
            trans('teacher::actions.admin.teacherUpdateAction.notExistTeacher')
        );
    }
}
