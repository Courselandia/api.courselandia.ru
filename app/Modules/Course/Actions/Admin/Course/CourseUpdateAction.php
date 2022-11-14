<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Actions\Admin\Course;

use DB;
use Cache;
use App\Models\Action;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Course\Enums\Currency;
use App\Modules\Course\Enums\Duration;
use App\Modules\Course\Enums\Language;
use App\Modules\Course\Enums\Status;
use App\Modules\Image\Entities\Image;
use App\Modules\Metatag\Actions\MetatagSetAction;
use App\Modules\Course\Entities\Course as CourseEntity;
use App\Modules\Course\Repositories\Course;
use App\Modules\Course\Repositories\CourseLevel;
use App\Modules\Course\Repositories\CourseLearn;
use App\Modules\Course\Repositories\CourseEmployment;
use App\Modules\Course\Repositories\CourseFeature;
use App\Modules\Course\Entities\CourseLevel as CourseLevelEntity;
use App\Modules\Course\Entities\CourseLearn as CourseLearnEntity;
use App\Modules\Course\Entities\CourseEmployment as CourseEmploymentEntity;
use App\Modules\Course\Entities\CourseFeature as CourseFeatureEntity;
use App\Modules\Salary\Enums\Level;
use Illuminate\Http\UploadedFile;
use ReflectionException;

/**
 * Класс действия для обновления курса.
 */
class CourseUpdateAction extends Action
{
    /**
     * Репозиторий публикаций.
     *
     * @var Course
     */
    private Course $course;

    /**
     * ID курса.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * Репозиторий уровней.
     *
     * @var CourseLevel
     */
    private CourseLevel $level;

    /**
     * Репозиторий тому что вы изучите на курсе.
     *
     * @var CourseLearn
     */
    private CourseLearn $learn;

    /**
     * Репозиторий помощи трудоустройства.
     *
     * @var CourseEmployment
     */
    private CourseEmployment $employmentRep;

    /**
     * Репозиторий особенностей курса.
     *
     * @var CourseFeature
     */
    private CourseFeature $feature;

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
     * Заголовок.
     *
     * @var string|null
     */
    public string|null $header = null;

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
     * Цена со скидкой.
     *
     * @var float|null
     */
    public float|null $price_discount = null;

    /**
     * Цена по кредиту.
     *
     * @var float|null
     */
    public float|null $price_recurrent_price = null;

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
     * Статус.
     *
     * @var Status|null
     */
    public Status|null $status = null;

    /**
     * Описание.
     *
     * @var string|null
     */
    public ?string $description = null;

    /**
     * Ключевые слова.
     *
     * @var string|null
     */
    public ?string $keywords = null;

    /**
     * Заголовок.
     *
     * @var string|null
     */
    public ?string $title = null;

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
     * @var string[]
     */
    public ?array $employments = null;

    /**
     * Особенности курса.
     *
     * @var array|null
     */
    public ?array $features = null;

    /**
     * Конструктор.
     *
     * @param Course $course Репозиторий публикаций.
     * @param CourseLevel $level Репозиторий уровней.
     * @param CourseLearn $learn Репозиторий тому что вы изучите на курсе.
     * @param CourseEmployment $employmentRep Репозиторий помощи трудоустройства.
     * @param CourseFeature $feature Репозиторий особенностей курса.
     */
    public function __construct(
        Course $course,
        CourseLevel $level,
        CourseLearn $learn,
        CourseEmployment $employmentRep,
        CourseFeature $feature
    ) {
        $this->course = $course;
        $this->level = $level;
        $this->learn = $learn;
        $this->employmentRep = $employmentRep;
        $this->feature = $feature;
    }

    /**
     * Метод запуска логики.
     *
     * @return CourseEntity Вернет результаты исполнения.
     * @throws RecordNotExistException
     * @throws ParameterInvalidException
     * @throws ReflectionException
     */
    public function run(): CourseEntity
    {
        $action = app(CourseGetAction::class);
        $action->id = $this->id;
        $courseEntity = $action->run();

        if ($courseEntity) {
            DB::transaction(function () use ($courseEntity) {
                $action = app(MetatagSetAction::class);
                $action->description = $this->description;
                $action->keywords = $this->keywords;
                $action->title = $this->title;
                $metatag = $action->run();

                $courseEntity->school_id = $this->school_id;
                $courseEntity->header = $this->header;
                $courseEntity->text = $this->text;
                $courseEntity->link = $this->link;
                $courseEntity->url = $this->url;
                $courseEntity->language = $this->language;
                $courseEntity->rating = $this->rating;
                $courseEntity->price = $this->price;
                $courseEntity->price_discount = $this->price_discount;
                $courseEntity->price_recurrent_price = $this->price_recurrent_price;
                $courseEntity->currency = $this->currency;
                $courseEntity->online = $this->online;
                $courseEntity->employment = $this->employment;
                $courseEntity->duration = $this->duration;
                $courseEntity->duration_unit = $this->duration_unit;
                $courseEntity->lessons_amount = $this->lessons_amount;
                $courseEntity->modules_amount = $this->modules_amount;
                $courseEntity->status = $this->status;
                $courseEntity->metatag_id = $metatag->id;

                if ($this->image) {
                    $courseEntity->image_small_id = $this->image;
                    $courseEntity->image_middle_id = $this->image;
                    $courseEntity->image_big_id = $this->image;
                }

                $id = $this->course->update($this->id, $courseEntity);
                $this->course->directionSync($id, $this->directions ?: []);
                $this->course->professionSync($id, $this->professions ?: []);
                $this->course->categorySync($id, $this->categories ?: []);
                $this->course->skillSync($id, $this->skills ?: []);
                $this->course->teacherSync($id, $this->teachers ?: []);
                $this->course->toolSync($id, $this->tools ?: []);

                $this->level->destroy(collect($courseEntity->levels)->pluck('id')->toArray(), true);

                if ($this->levels) {
                    foreach ($this->levels as $level) {
                        $entity = new CourseLevelEntity();
                        $entity->course_id = $id;
                        $entity->level = Level::from($level);

                        $this->level->create($entity);
                    }
                }

                $this->learn->destroy(collect($courseEntity->learns)->pluck('id')->toArray(), true);

                if ($this->learns) {
                    foreach ($this->learns as $learn) {
                        $entity = new CourseLearnEntity();
                        $entity->course_id = $id;
                        $entity->text = $learn;

                        $this->learn->create($entity);
                    }
                }

                $this->employmentRep->destroy(collect($courseEntity->employments)->pluck('id')->toArray(), true);

                if ($this->employments) {
                    foreach ($this->employments as $employment) {
                        $entity = new CourseEmploymentEntity();
                        $entity->course_id = $id;
                        $entity->text = $employment;

                        $this->employmentRep->create($entity);
                    }
                }

                $this->feature->destroy(collect($courseEntity->features)->pluck('id')->toArray(), true);

                if ($this->features) {
                    foreach ($this->features as $feature) {
                        $entity = new CourseFeatureEntity();
                        $entity->course_id = $id;
                        $entity->text = $feature['text'];
                        $entity->icon = $feature['icon'];

                        $this->feature->create($entity);
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
