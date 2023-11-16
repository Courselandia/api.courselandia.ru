<?php
/**
 * Модуль Учителей.
 * Этот модуль содержит все классы для работы с учителями.
 *
 * @package App\Modules\Teacher
 */

namespace App\Modules\Teacher\Actions\Admin\Teacher;

use App\Modules\Analyzer\Actions\Admin\AnalyzerUpdateAction;
use Carbon\Carbon;
use Config;
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
use App\Modules\Image\Entities\Image;
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
     * ID учителя.
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
     * Ссылка.
     *
     * @var string|null
     */
    public ?string $link = null;

    /**
     * Текст.
     *
     * @var string|null
     */
    public ?string $text = null;

    /**
     * Рейтинг.
     *
     * @var float|null
     */
    public ?float $rating = null;

    /**
     * Город.
     *
     * @var string|null
     */
    public ?string $city = null;

    /**
     * Комментарий.
     *
     * @var string|null
     */
    public ?string $comment = null;

    /**
     * Скопирован.
     *
     * @var string|null
     */
    public ?string $copied = null;

    /**
     * Изображение.
     *
     * @var int|UploadedFile|Image|null
     */
    public int|UploadedFile|Image|null $image = null;

    /**
     * Порезанное изображение в бинарных данных.
     *
     * @var string|null
     */
    public string|null $imageCropped = null;

    /**
     * Опции порезанного изображения.
     *
     * @var array|null
     */
    public array|null $imageCroppedOptions = null;

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
     * ID направлений.
     *
     * @var int[]
     */
    public ?array $directions = null;

    /**
     * Опыт работы учителя.
     *
     * @var array|null
     */
    public ?array $experiences = null;

    /**
     * Социальные сети учителя.
     *
     * @var array|null
     */
    public ?array $socialMedias = null;

    /**
     * ID школ.
     *
     * @var int[]
     */
    public ?array $schools = null;

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
        $action = app(TeacherGetAction::class);
        $action->id = $this->id;
        $teacherEntity = $action->run();

        if ($teacherEntity) {
            DB::transaction(function () use ($teacherEntity) {
                $countTeacherCourses = Course::where('courses.status', Status::ACTIVE->value)
                    ->whereHas('school', function ($query) {
                        $query->where('schools.status', true);
                    })
                    ->whereHas('teachers', function ($query) {
                        $query->where('teachers.id', $this->id);
                    })
                    ->count();

                $templateValues = [
                    'teacher' => $this->name,
                    'countTeacherCourses' => $countTeacherCourses,
                ];

                $template = new Template();

                $action = app(MetatagSetAction::class);
                $action->description = $template->convert($this->description_template, $templateValues);
                $action->title = $template->convert($this->title_template, $templateValues);
                $action->description_template = $this->description_template;
                $action->title_template = $this->title_template;
                $action->keywords = $this->keywords;
                $action->id = $teacherEntity->metatag_id ?: null;

                $teacherEntity->metatag_id = $action->run()->id;
                $teacherEntity->name = Typography::process($this->name, true);
                $teacherEntity->link = $this->link;
                $teacherEntity->city = $this->city;
                $teacherEntity->comment = $this->comment;
                $teacherEntity->copied = $this->copied;
                $teacherEntity->text = Typography::process($this->text);
                $teacherEntity->rating = $this->rating;
                $teacherEntity->status = $this->status;

                if ($this->image) {
                    $teacherEntity->image_big_id = $this->image;
                    $teacherEntity->image_small_id = $this->image;
                }

                if ($this->imageCropped) {
                    list(, $data) = explode(';', $this->imageCropped);
                    list(, $data) = explode(',', $data);
                    $data = base64_decode($data);
                    $imageName = ImageStore::tmp('webp');
                    file_put_contents($imageName, $data);

                    $teacherEntity->image_middle_id = new UploadedFile($imageName, basename($imageName), 'image/webp');
                    $teacherEntity->image_cropped_options = $this->imageCroppedOptions;
                }

                $teacher = Teacher::find($this->id);
                $teacher->update($teacherEntity->toArray());
                $teacher->directions()->sync($this->directions ?: []);
                $teacher->schools()->sync($this->schools ?: []);

                TeacherExperience::whereIn('id', collect($teacherEntity->experiences)->pluck('id')->toArray())
                    ->forceDelete();

                if ($this->experiences) {
                    foreach ($this->experiences as $experience) {
                        $entity = new TeacherExperienceEntity();
                        $entity->teacher_id = $teacher->id;
                        $entity->place = $experience['place'];
                        $entity->position = $experience['position'];
                        $entity->weight = $experience['weight'];

                        $entity->started = $experience['started'] ? Carbon::createFromFormat(
                            'Y-m-d',
                            $experience['started']
                        ) : null;

                        $entity->finished = $experience['finished'] ? Carbon::createFromFormat(
                            'Y-m-d',
                            $experience['finished']
                        ) : null;

                        TeacherExperience::create($entity->toArray());
                    }
                }

                TeacherSocialMedia::whereIn('id', collect($teacherEntity->social_medias)->pluck('id')->toArray())
                    ->forceDelete();

                if ($this->socialMedias) {
                    foreach ($this->socialMedias as $socialMedia) {
                        $entity = new TeacherSocialMediaEntity();
                        $entity->teacher_id = $teacher->id;
                        $entity->name = SocialMedia::from($socialMedia['name']);
                        $entity->value = $socialMedia['value'];

                        TeacherSocialMedia::create($entity->toArray());
                    }
                }
            });

            Cache::tags(['catalog', 'teacher', 'direction', 'school'])->flush();

            if (!$this->copied) {
                $action = app(AnalyzerUpdateAction::class);
                $action->id = $this->id;
                $action->model = Teacher::class;
                $action->category = 'teacher.text';
                $action->run();
            }

            $action = app(TeacherGetAction::class);
            $action->id = $this->id;

            return $action->run();
        }

        throw new RecordNotExistException(
            trans('teacher::actions.admin.teacherUpdateAction.notExistTeacher')
        );
    }
}
