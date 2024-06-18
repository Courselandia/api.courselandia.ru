<?php
/**
 * Модуль Учителей.
 * Этот модуль содержит все классы для работы с учителями.
 *
 * @package App\Modules\Teacher
 */

namespace App\Modules\Teacher\Actions\Admin\Teacher;

use DB;
use Cache;
use ImageStore;
use Throwable;
use Typography;
use App\Models\Action;
use App\Modules\Metatag\Actions\MetatagSetAction;
use App\Modules\Metatag\Template\Template;
use App\Modules\Metatag\Template\TemplateException;
use App\Modules\Teacher\Entities\Teacher as TeacherEntity;
use App\Modules\Teacher\Models\Teacher;
use Illuminate\Http\UploadedFile;
use App\Modules\Teacher\Models\TeacherExperience;
use App\Modules\Teacher\Entities\TeacherExperience as TeacherExperienceEntity;
use App\Modules\Teacher\Models\TeacherSocialMedia;
use App\Modules\Teacher\Entities\TeacherSocialMedia as TeacherSocialMediaEntity;
use App\Modules\Analyzer\Actions\Admin\AnalyzerUpdateAction;
use App\Modules\Metatag\Data\MetatagSet;
use App\Modules\Teacher\Data\TeacherCreate;
use App\Modules\Teacher\Enums\SocialMedia;
use App\Modules\Direction\Models\Direction;

/**
 * Класс действия для создания учителя.
 */
class TeacherCreateAction extends Action
{
    /**
     * Данные для создания учителя.
     *
     * @var TeacherCreate
     */
    private TeacherCreate $data;

    /**
     * @param TeacherCreate $data Данные для создания учителя.
     */
    public function __construct(TeacherCreate $data)
    {
        $this->data = $data;
    }

    /**
     * Метод запуска логики.
     *
     * @return TeacherEntity Вернет результаты исполнения.
     * @throws TemplateException
     * @throws Throwable
     */
    public function run(): TeacherEntity
    {
        $id = DB::transaction(function () {
            $template = new Template();
            $direction = null;

            if ($this->data->directions && count($this->data->directions)) {
                $directionModel = Direction::find($this->data->directions[0]);
                $direction = $directionModel?->name;
            }

            $templateValues = [
                'teacher' => $this->data->name,
                'countTeacherCourses' => 0,
                'direction' => $direction,
            ];

            $action = new MetatagSetAction(MetatagSet::from([
                'description' => Typography::process($template->convert($this->data->description_template, $templateValues), true),
                'title' => Typography::process($template->convert($this->data->title_template, $templateValues), true),
                'description_template' => $this->data->description_template,
                'title_template' => $this->data->title_template,
                'keywords' => $this->data->keywords,
            ]));

            $metatag = $action->run();

            $teacherEntity = TeacherEntity::from([
                ...$this->data
                    ->except('directions', 'schools', 'experiences', 'socialMedias')
                    ->toArray(),
                'name' => Typography::process($this->data->name, true),
                'text' => Typography::process($this->data->text),
                'metatag_id' => $metatag->id,
            ]);

            $teacherEntity = $teacherEntity->toArray();

            $teacherEntity['image_small_id'] = $this->data->image;
            $teacherEntity['image_big_id'] = $this->data->image;

            if ($this->data->imageCropped) {
                list(, $data) = explode(';', $this->data->imageCropped);
                list(, $data) = explode(',', $data);
                $data = base64_decode($data);
                $imageName = ImageStore::tmp('webp');
                file_put_contents($imageName, $data);

                $teacherEntity['image_middle_id'] = new UploadedFile($imageName, basename($imageName), 'image/webp');
                $teacherEntity['image_cropped_options'] = $this->data->imageCroppedOptions;
            }

            $teacher = Teacher::create($teacherEntity);
            $teacher->directions()->sync($this->data->directions ?: []);
            $teacher->schools()->sync($this->data->schools ?: []);

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

            Cache::tags(['catalog', 'teacher'])->flush();

            if (!$this->data->copied) {
                $action = new AnalyzerUpdateAction($teacher->id, Teacher::class, 'teacher.text');
                $action->run();
            }

            return $teacher->id;
        });

        $action = new TeacherGetAction($id);

        return $action->run();
    }
}
