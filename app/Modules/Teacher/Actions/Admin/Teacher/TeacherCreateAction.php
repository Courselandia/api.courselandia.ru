<?php
/**
 * Модуль Учителей.
 * Этот модуль содержит все классы для работы с учителями.
 *
 * @package App\Modules\Teacher
 */

namespace App\Modules\Teacher\Actions\Admin\Teacher;

use App\Modules\Teacher\Enums\SocialMedia;
use DB;
use Cache;
use Config;
use Throwable;
use Typography;
use Carbon\Carbon;
use App\Models\Action;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Image\Entities\Image;
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

/**
 * Класс действия для создания учителя.
 */
class TeacherCreateAction extends Action
{
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
     * Город.
     *
     * @var string|null
     */
    public ?string $city = null;

    /**
     * Скопирован.
     *
     * @var string|null
     */
    public ?string $copied = null;

    /**
     * Рейтинг.
     *
     * @var float|null
     */
    public ?float $rating = null;

    /**
     * Изображение.
     *
     * @var int|UploadedFile|Image|null
     */
    public int|UploadedFile|Image|null $image = null;

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
     * @throws ParameterInvalidException
     * @throws TemplateException
     * @throws Throwable
     */
    public function run(): TeacherEntity
    {
        $id = DB::transaction(function () {
            $action = app(MetatagSetAction::class);
            $template = new Template();

            $templateValues = [
                'teacher' => $this->name,
                'countTeacherCourses' => 0,
            ];

            $action->description = $template->convert($this->description_template, $templateValues);
            $action->title = $template->convert($this->title_template, $templateValues);
            $action->description_template = $this->description_template;
            $action->title_template = $this->title_template;
            $action->keywords = $this->keywords;

            $metatag = $action->run();

            $teacherEntity = new TeacherEntity();
            $teacherEntity->name = Typography::process($this->name, true);
            $teacherEntity->link = $this->link;
            $teacherEntity->text = Typography::process($this->text);
            $teacherEntity->rating = $this->rating;
            $teacherEntity->city = $this->city;
            $teacherEntity->copied = $this->copied;
            $teacherEntity->image_small_id = $this->image;
            $teacherEntity->image_middle_id = $this->image;
            $teacherEntity->image_big_id = $this->image;
            $teacherEntity->status = $this->status;
            $teacherEntity->metatag_id = $metatag->id;

            $teacher = Teacher::create($teacherEntity->toArray());
            $teacher->directions()->sync($this->directions ?: []);
            $teacher->schools()->sync($this->schools ?: []);

            if ($this->experiences) {
                foreach ($this->experiences as $experience) {
                    $entity = new TeacherExperienceEntity();
                    $entity->teacher_id = $teacher->id;
                    $entity->place = $experience['place'];
                    $entity->position = $experience['position'];
                    $entity->weight = $experience['weight'];

                    $action->started = $experience['started'] ? Carbon::createFromFormat(
                        'Y-m-d H:i:s O',
                        $experience['started']
                    )->setTimezone(Config::get('app.timezone')) : null;

                    $action->finished = $experience['finished'] ? Carbon::createFromFormat(
                        'Y-m-d H:i:s O',
                        $experience['finished']
                    )->setTimezone(Config::get('app.timezone')) : null;

                    TeacherExperience::create($entity->toArray());
                }
            }

            if ($this->socialMedias) {
                foreach ($this->socialMedias as $socialMedia) {
                    $entity = new TeacherSocialMediaEntity();
                    $entity->teacher_id = $teacher->id;
                    $entity->name = SocialMedia::from($socialMedia['name']);
                    $entity->value = $socialMedia['value'];

                    TeacherSocialMedia::create($entity->toArray());
                }
            }

            return $teacher->id;
        });

        Cache::tags(['catalog', 'teacher', 'direction', 'school'])->flush();

        $action = app(TeacherGetAction::class);
        $action->id = $id;

        return $action->run();
    }
}
