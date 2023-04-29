<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Actions\Admin\Course;

use App\Models\Action;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Course\Entities\Course as CourseEntity;
use App\Modules\Course\Entities\CourseFeature as CourseFeatureEntity;
use App\Modules\Course\Entities\CourseLearn as CourseLearnEntity;
use App\Modules\Course\Entities\CourseLevel as CourseLevelEntity;
use App\Modules\Course\Enums\Currency;
use App\Modules\Course\Enums\Duration;
use App\Modules\Course\Enums\Language;
use App\Modules\Course\Enums\Status;
use App\Modules\Course\Models\Course;
use App\Modules\Course\Models\CourseFeature;
use App\Modules\Course\Models\CourseLearn;
use App\Modules\Course\Models\CourseLevel;
use App\Modules\Course\Normalize\Data;
use App\Modules\Image\Entities\Image;
use App\Modules\Metatag\Actions\MetatagSetAction;
use App\Modules\Metatag\Template\Template;
use App\Modules\Salary\Enums\Level;
use Cache;
use DB;
use Illuminate\Http\UploadedFile;
use Throwable;

/**
 * Класс действия для обновления курса.
 */
class CourseUpdateAction extends Action
{
    /**
     * ID курса.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * ID школы.
     *
     * @var string|int|null
     */
    public string|int|null $school_id = null;

    /**
     * Изображение.
     *
     * @var int|UploadedFile|Image|null
     */
    public int|UploadedFile|Image|null $image = null;

    /**
     * Название.
     *
     * @var string|null
     */
    public string|null $name = null;

    /**
     * Шаблон заголовка.
     *
     * @var string|null
     */
    public string|null $header_template = null;

    /**
     * Описание.
     *
     * @var string|null
     */
    public string|null $text = null;

    /**
     * Ссылка.
     *
     * @var string|null
     */
    public string|null $link = null;

    /**
     * URL на курс.
     *
     * @var string|null
     */
    public string|null $url = null;

    /**
     * Язык курса.
     *
     * @var Language|null
     */
    public Language|null $language = null;

    /**
     * Рейтинг.
     *
     * @var float|null
     */
    public float|null $rating = null;

    /**
     * Цена.
     *
     * @var float|null
     */
    public float|null $price = null;

    /**
     * Старая цена.
     *
     * @var float|null
     */
    public float|null $price_old = null;

    /**
     * Цена по кредиту.
     *
     * @var float|null
     */
    public float|null $price_recurrent = null;

    /**
     * Валюта.
     *
     * @var Currency|null
     */
    public Currency|null $currency = null;

    /**
     * Онлайн статус.
     *
     * @var bool|null
     */
    public bool|null $online = null;

    /**
     * С трудоустройством.
     *
     * @var bool|null
     */
    public bool|null $employment = null;

    /**
     * Продолжительность.
     *
     * @var int|null
     */
    public int|null $duration = null;

    /**
     * Единица измерения продолжительности.
     *
     * @var Duration|null
     */
    public Duration|null $duration_unit = null;

    /**
     * Количество уроков.
     *
     * @var int|null
     */
    public int|null $lessons_amount = null;

    /**
     * Количество модулей.
     *
     * @var int|null
     */
    public int|null $modules_amount = null;

    /**
     * Программа курса.
     *
     * @var array|null
     */
    public array|null $program = null;

    /**
     * Статус.
     *
     * @var Status|null
     */
    public Status|null $status = null;

    /**
     * Шаблон описание.
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
     * ID профессий.
     *
     * @var int[]
     */
    public ?array $professions = null;

    /**
     * ID категорий.
     *
     * @var int[]
     */
    public ?array $categories = null;

    /**
     * ID навыков.
     *
     * @var int[]
     */
    public ?array $skills = null;

    /**
     * ID учителей.
     *
     * @var int[]
     */
    public ?array $teachers = null;

    /**
     * ID инструментов.
     *
     * @var int[]
     */
    public ?array $tools = null;

    /**
     * ID как проходит обучение.
     *
     * @var int[]
     */
    public ?array $processes = null;

    /**
     * Уровни.
     *
     * @var Level[]
     */
    public ?array $levels = null;

    /**
     * Что будет изучено.
     *
     * @var string[]
     */
    public ?array $learns = null;

    /**
     * Помощь в трудоустройстве.
     *
     * @var int[]
     */
    public ?array $employments = null;

    /**
     * Особенности курса.
     *
     * @var array|null
     */
    public ?array $features = null;

    /**
     * Метод запуска логики.
     *
     * @return CourseEntity Вернет результаты исполнения.
     * @throws RecordNotExistException
     * @throws ParameterInvalidException|Throwable
     */
    public function run(): CourseEntity
    {
        $action = app(CourseGetAction::class);
        $action->id = $this->id;
        $courseEntity = $action->run();

        if ($courseEntity) {
            DB::transaction(function () use ($courseEntity) {
                $templateValues = [
                    'course' => $this->name,
                    'school' => $courseEntity->school->name,
                    'price' => $this->price,
                    'currency' => $this->currency,
                ];

                $template = new Template();

                $action = app(MetatagSetAction::class);
                $action->description = $template->convert($this->description_template, $templateValues);
                $action->title = $template->convert($this->title_template, $templateValues);
                $action->description_template = $this->description_template;
                $action->title_template = $this->title_template;
                $action->keywords = $this->keywords;
                $action->id = $courseEntity->metatag_id ?: null;

                $courseEntity->metatag_id = $action->run()->id;
                $courseEntity->school_id = $this->school_id;
                $courseEntity->name = $this->name;
                $courseEntity->header = $template->convert($this->header_template, $templateValues);
                $courseEntity->header_template = $this->header_template;
                $courseEntity->text = $this->text;
                $courseEntity->link = $this->link;
                $courseEntity->url = $this->url;
                $courseEntity->language = $this->language;
                $courseEntity->rating = $this->rating;
                $courseEntity->price = $this->price;
                $courseEntity->price_old = $this->price_old;
                $courseEntity->price_recurrent = $this->price_recurrent;
                $courseEntity->currency = $this->currency;
                $courseEntity->online = $this->online;
                $courseEntity->employment = $this->employment;
                $courseEntity->duration = $this->duration;
                $courseEntity->duration_unit = $this->duration_unit;
                $courseEntity->lessons_amount = $this->lessons_amount;
                $courseEntity->modules_amount = $this->modules_amount;
                $courseEntity->program = $this->program;

                $courseEntity->direction_ids = Data::getDirections($this->directions ?: []);
                $courseEntity->profession_ids = Data::getProfessions($this->professions ?: []);
                $courseEntity->category_ids = Data::getCategories($this->categories ?: []);
                $courseEntity->skill_ids = Data::getSkills($this->skills ?: []);
                $courseEntity->teacher_ids = Data::getTeachers($this->teachers ?: []);
                $courseEntity->tool_ids = Data::getTools($this->tools ?: []);
                $courseEntity->level_values = $this->levels;
                $courseEntity->has_active_school = Data::isActiveSchool($this->school_id);

                $courseEntity->status = $this->status;

                if ($this->image) {
                    $courseEntity->image_small_id = $this->image;
                    $courseEntity->image_middle_id = $this->image;
                    $courseEntity->image_big_id = $this->image;
                }

                $course = Course::find($this->id);
                $course->update($courseEntity->toArray());
                $course->directions()->sync($this->directions ?: []);
                $course->professions()->sync($this->professions ?: []);
                $course->categories()->sync($this->categories ?: []);
                $course->skills()->sync($this->skills ?: []);
                $course->teachers()->sync($this->teachers ?: []);
                $course->tools()->sync($this->tools ?: []);
                $course->processes()->sync($this->processes ?: []);
                $course->employments()->sync($this->employments ?: []);

                CourseLevel::whereIn('id', collect($courseEntity->levels)->pluck('id')->toArray())
                    ->forceDelete();

                if ($this->levels) {
                    foreach ($this->levels as $level) {
                        $entity = new CourseLevelEntity();
                        $entity->course_id = $this->id;
                        $entity->level = Level::from($level);

                        CourseLevel::create($entity->toArray());
                    }
                }

                CourseLearn::whereIn('id', collect($courseEntity->learns)->pluck('id')->toArray())
                    ->forceDelete();

                if ($this->learns) {
                    foreach ($this->learns as $learn) {
                        $entity = new CourseLearnEntity();
                        $entity->course_id = $this->id;
                        $entity->text = $learn;

                        CourseLearn::create($entity->toArray());
                    }
                }

                CourseFeature::whereIn('id', collect($courseEntity->features)->pluck('id')->toArray())
                    ->forceDelete();

                if ($this->features) {
                    foreach ($this->features as $feature) {
                        $entity = new CourseFeatureEntity();
                        $entity->course_id = $this->id;
                        $entity->text = $feature['text'];
                        $entity->icon = $feature['icon'];

                        CourseFeature::create($entity->toArray());
                    }
                }
            });

            Cache::tags([
                'course',
                'direction',
                'profession',
                'category',
                'skill',
                'teacher',
                'tool',
                'process',
                'employment',
                'review',
            ])->flush();

            $action = app(CourseGetAction::class);
            $action->id = $this->id;

            return $action->run();
        }

        throw new RecordNotExistException(
            trans('course::actions.admin.courseUpdateAction.notExistCourse')
        );
    }
}
