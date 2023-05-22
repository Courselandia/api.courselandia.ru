<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Models;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use SVG\SVG;
use Size;
use ImageStore;
use Exception;
use Eloquent;
use App\Modules\Article\Models\Article;
use App\Modules\Course\Enums\Duration;
use App\Modules\Course\Enums\Language;
use App\Modules\Metatag\Models\Metatag;
use App\Modules\Review\Models\Review;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Enums\EnumList;
use App\Models\Rep\RepositoryQueryBuilder;
use App\Modules\Category\Models\Category;
use App\Modules\Course\Enums\Currency;
use App\Modules\Course\Enums\Status;
use App\Modules\Direction\Models\Direction;
use App\Modules\Image\Entities\Image as ImageEntity;
use App\Modules\Image\Helpers\Image;
use App\Modules\Profession\Models\Profession;
use App\Modules\School\Models\School;
use App\Modules\Skill\Models\Skill;
use App\Modules\Teacher\Models\Teacher;
use App\Modules\Tool\Models\Tool;
use App\Modules\Process\Models\Process;
use App\Modules\Employment\Models\Employment;
use CodeBuds\WebPConverter\WebPConverter;
use App\Models\Delete;
use App\Models\Validate;
use App\Models\Sortable;
use EloquentFilter\Filterable;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\UploadedFile;
use JetBrains\PhpStorm\ArrayShape;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Modules\Course\Database\Factories\CourseFactory;
use App\Modules\Course\Filters\CourseFilter;

/**
 * Класс модель для таблицы курсов на основе Eloquent.
 *
 * @property int|string $id ID курса.
 * @property string $uuid ID источника курса.
 * @property int|string $metatag_id ID метатэгов.
 * @property int|string $school_id ID школы.
 * @property int|string|array|UploadedFile|ImageEntity $image_big_id Большая картинка.
 * @property int|string|array|UploadedFile|ImageEntity $image_middle_id Средняя картинка.
 * @property int|string|array|UploadedFile|ImageEntity $image_small_id Маленькая картинка.
 * @property string $name Название.
 * @property string $header Заголовок.
 * @property string $header_template Шаблон заголовок.
 * @property string $text Описание.
 * @property string $name_morphy Название морфологизированное.
 * @property string $text_morphy Описание морфологизированное.
 * @property string $link Ссылка.
 * @property string $url URL на курс.
 * @property string $language Язык курса.
 * @property float $rating Рейтинг.
 * @property float $price Цена.
 * @property float $price_old Старая цена.
 * @property float $price_recurrent Цена по кредиту.
 * @property string $currency Валюта.
 * @property boolean $online Онлайн.
 * @property boolean $employment С трудоустройством.
 * @property int $duration Продолжительность.
 * @property float $duration_rate Рейт продолжительность.
 * @property string $duration_unit Единица измерения продолжительности.
 * @property int $lessons_amount Количество уроков.
 * @property int $modules_amount Количество модулей.
 * @property array $program Программа курса.
 * @property array $direction_ids Активные направления.
 * @property array $profession_ids Активные профессии.
 * @property array $category_ids Активные категории.
 * @property array $skill_ids Активные навыки.
 * @property array $teacher_ids Активные учителя.
 * @property array $tool_ids Активные инструменты.
 * @property array $level_values Активные уровни.
 * @property boolean $has_active_school Признак если активная школа.
 * @property string $status Статус.
 *
 * @property-read Metatag $metatag
 * @property-read School $school
 * @property-read Review[] $reviews
 * @property-read Direction[] $directions
 * @property-read Profession[] $professions
 * @property-read Category[] $categories
 * @property-read Skill[] $skills
 * @property-read Teacher[] $teachers
 * @property-read Tool[] $tools
 * @property-read Process[] $processes
 * @property-read Employment[] $employments
 * @property-read CourseLevel[] $levels
 * @property-read CourseLearn[] $learns
 * @property-read CourseFeature[] $features
 * @property-read Article[] $articles
 */
class Course extends Eloquent
{
    use Delete;
    use HasFactory;
    use Sortable;
    use SoftDeletes;
    use Validate;
    use Filterable;

    /**
     * Типизирование атрибутов.
     *
     * @var array
     */
    protected $casts = [
        'program' => 'array',
        'direction_ids' => 'array',
        'profession_ids' => 'array',
        'category_ids' => 'array',
        'skill_ids' => 'array',
        'teacher_ids' => 'array',
        'tool_ids' => 'array',
        'level_values' => 'array',
    ];

    /**
     * Атрибуты, для которых разрешено массовое назначение.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'uuid',
        'metatag_id',
        'school_id',
        'image_big_id',
        'image_middle_id',
        'image_small_id',
        'name',
        'header',
        'header_template',
        'text',
        'name_morphy',
        'text_morphy',
        'link',
        'url',
        'language',
        'rating',
        'price',
        'price_old',
        'price_recurrent',
        'currency',
        'online',
        'employment',
        'duration',
        'duration_rate',
        'duration_unit',
        'lessons_amount',
        'modules_amount',
        'program',
        'direction_ids',
        'profession_ids',
        'category_ids',
        'skill_ids',
        'teacher_ids',
        'tool_ids',
        'level_values',
        'has_active_school',
        'status',
    ];

    /**
     * Метод, который должен вернуть все правила валидации.
     *
     * @return array Вернет массив правил.
     */
    #[ArrayShape([
        'uuid' => 'string',
        'metatag_id' => 'string',
        'school_id' => 'string',
        'image_big_id' => 'string',
        'image_middle_id' => 'string',
        'image_small_id' => 'string',
        'name' => 'string',
        'header' => 'string',
        'header_template' => 'string',
        'text' => 'string',
        'name_morphy' => 'string',
        'text_morphy' => 'string',
        'link' => 'string',
        'url' => 'string',
        'language' => 'string',
        'rating' => 'string',
        'price' => 'string',
        'price_old' => 'string',
        'price_recurrent' => 'string',
        'currency' => 'string',
        'online' => 'string',
        'employment' => 'string',
        'duration' => 'string',
        'duration_rate' => 'string',
        'duration_unit' => 'string',
        'lessons_amount' => 'string',
        'modules_amount' => 'string',
        'program' => 'string',
        'direction_ids' => 'string',
        'profession_ids' => 'string',
        'category_ids' => 'string',
        'skill_ids' => 'string',
        'teacher_ids' => 'string',
        'tool_ids' => 'string',
        'level_values' => 'string',
        'has_active_school' => 'string',
        'status' => 'string',
    ])] protected function getRules(): array
    {
        return [
            'uuid' => 'max:191',
            'metatag_id' => 'digits_between:0,20',
            'school_id' => 'required|digits_between:0,20|exists_soft:schools,id',
            'name' => 'required|between:1,191',
            'header' => 'max:191',
            'header_template' => 'max:191',
            'text' => 'max:5000',
            'name_morphy' => 'max:191',
            'text_morphy' => 'max:5000',
            'link' => 'required|between:1,191|alpha_dash',
            'url' => 'required|url',
            'language' => 'in:' . implode(',', EnumList::getValues(Language::class)),
            'rating' => 'nullable|float|float_between:0,5',
            'price' => 'nullable|float|float_between:0,999999',
            'price_old' => 'nullable|float|float_between:0,999999',
            'price_recurrent' => 'nullable|float|float_between:0,999999',
            'currency' => 'in:' . implode(',', EnumList::getValues(Currency::class)),
            'online' => 'boolean',
            'employment' => 'boolean',
            'duration' => 'integer|digits_between:0,5',
            'duration_rate' => 'nullable|float',
            'duration_unit' => 'in:' . implode(',', EnumList::getValues(Duration::class)),
            'lessons_amount' => 'integer|digits_between:0,5',
            'modules_amount' => 'integer|digits_between:0,5',
            'program' => 'json',
            'direction_ids' => 'json',
            'profession_ids' => 'json',
            'category_ids' => 'json',
            'skill_ids' => 'json',
            'teacher_ids' => 'json',
            'tool_ids' => 'json',
            'level_values' => 'json',
            'has_active_school' => 'boolean',
            'status' => 'required|in:' . implode(',', EnumList::getValues(Status::class)),
        ];
    }

    /**
     * Метод, который должен вернуть все названия атрибутов.
     *
     * @return array Массив возможных ошибок валидации.
     */
    protected function getNames(): array
    {
        return [
            'uuid' => trans('course::models.course.uuid'),
            'metatag_id' => trans('course::models.course.metatagId'),
            'school_id' => trans('course::models.course.schoolId'),
            'image_big_id' => trans('course::models.course.imageBigId'),
            'image_middle_id' => trans('course::models.course.imageMiddleId'),
            'image_small_id' => trans('course::models.course.imageSmallId'),
            'name' => trans('course::models.course.name'),
            'header' => trans('course::models.course.header'),
            'header_template' => trans('course::models.course.headerTemplate'),
            'text' => trans('course::models.course.text'),
            'name_morphy' => trans('course::models.course.headerMorphy'),
            'text_morphy' => trans('course::models.course.textMorphy'),
            'link' => trans('course::models.course.link'),
            'url' => trans('course::models.course.url'),
            'language' => trans('course::models.course.language'),
            'rating' => trans('course::models.course.rating'),
            'price' => trans('course::models.course.price'),
            'price_old' => trans('course::models.course.priceOld'),
            'price_recurrent' => trans('course::models.course.priceRecurrentPrice'),
            'currency' => trans('course::models.course.currency'),
            'online' => trans('course::models.course.online'),
            'employment' => trans('course::models.course.employment'),
            'duration' => trans('course::models.course.duration'),
            'duration_rate' => trans('course::models.course.durationRate'),
            'duration_unit' => trans('course::models.course.durationUnit'),
            'lessons_amount' => trans('course::models.course.lessonsAmount'),
            'modules_amount' => trans('course::models.course.modulesAmount'),
            'program' => trans('course::models.course.program'),
            'direction_ids' => trans('course::models.course.directionIds'),
            'profession_ids' => trans('course::models.course.professionIds'),
            'category_ids' => trans('course::models.course.categoryIds'),
            'skill_ids' => trans('course::models.course.skillIds'),
            'teacher_ids' => trans('course::models.course.teacherIds'),
            'tool_ids' => trans('course::models.course.toolIds'),
            'level_values' => trans('course::models.course.levelValues'),
            'has_active_school' => trans('course::models.course.hasActiveSchool'),
            'status' => trans('course::models.course.status'),
        ];
    }

    /**
     * Класс фильтр.
     *
     * @return string Название класса фильтра.
     */
    public function modelFilter(): string
    {
        return $this->provideFilter(CourseFilter::class);
    }

    /**
     * Создание новой фабрики для модели.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return CourseFactory::new();
    }

    /**
     * Получить метатэги.
     *
     * @return BelongsTo Модель метатэгов.
     */
    public function metatag(): BelongsTo
    {
        return $this->belongsTo(Metatag::class);
    }

    /**
     * Получить школу.
     *
     * @return BelongsTo Модель школы.
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Направления этого курса.
     *
     * @return BelongsToMany Модели направлений.
     */
    public function directions(): BelongsToMany
    {
        return $this->belongsToMany(Direction::class);
    }

    /**
     * Профессии этого курса.
     *
     * @return BelongsToMany Модели направлений.
     */
    public function professions(): BelongsToMany
    {
        return $this->belongsToMany(Profession::class);
    }

    /**
     * Категории этого курса.
     *
     * @return BelongsToMany Модели категорий.
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'course_category');
    }

    /**
     * Навыки этого курса.
     *
     * @return BelongsToMany Модели навыков.
     */
    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class);
    }

    /**
     * Учителя этого курса.
     *
     * @return BelongsToMany Модели учителей.
     */
    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(Teacher::class);
    }

    /**
     * Инструменты этого курса.
     *
     * @return BelongsToMany Модели инструментов.
     */
    public function tools(): BelongsToMany
    {
        return $this->belongsToMany(Tool::class);
    }

    /**
     * Как проходит обучение на курсе.
     *
     * @return BelongsToMany Модели как проходит обучение.
     */
    public function processes(): BelongsToMany
    {
        return $this->belongsToMany(Process::class);
    }

    /**
     * Трудоустройство для этого курса.
     *
     * @return BelongsToMany Модели трудоустройства.
     */
    public function employments(): BelongsToMany
    {
        return $this->belongsToMany(Employment::class);
    }

    /**
     * Уровни этого курса.
     *
     * @return HasMany Модели уровня.
     */
    public function levels(): HasMany
    {
        return $this->hasMany(CourseLevel::class);
    }

    /**
     * Чему научитесь на этом курсе.
     *
     * @return HasMany Модели чему научитесь.
     */
    public function learns(): HasMany
    {
        return $this->hasMany(CourseLearn::class);
    }

    /**
     * Особенности курса.
     *
     * @return HasMany Модели особенностей курса.
     */
    public function features(): HasMany
    {
        return $this->hasMany(CourseFeature::class);
    }

    /**
     * Отзывы этого курса.
     *
     * @return HasMany Модели отзывов.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Статьи написанные искусственным интеллектом.
     *
     * @return MorphMany Модели статей.
     */
    public function articles(): MorphMany
    {
        return $this->morphMany(Article::class, 'articleable');
    }

    /**
     * Преобразователь атрибута - запись: маленькое изображение.
     *
     * @param  mixed  $value  Значение атрибута.
     *
     * @return void
     * @throws FileNotFoundException|Exception
     */
    public function setImageSmallIdAttribute(mixed $value): void
    {
        $name = 'image_small_id';
        $folder = 'courses';

        $this->attributes[$name] = Image::set(
            $name,
            $value,
            function (string $name, UploadedFile $value) use ($folder) {
                if ($value->getClientOriginalExtension() === 'svg') {
                    $imageSvg = SVG::fromFile($value->path());
                    $imageRaster = $imageSvg->toRasterImage(600, 600);
                    $path = ImageStore::tmp('png');
                    imagepng($imageRaster, $path);

                    $image = Size::make($path);
                } else {
                    $path = ImageStore::tmp($value->getClientOriginalExtension());
                    $image = Size::make($value);
                }

                $image->fit(150, 150)->save($path);

                $imageWebp = WebPConverter::createWebpImage($path, ['saveFile' => true]);

                ImageStore::setFolder($folder);
                $image = new ImageEntity();
                $image->path = $imageWebp['path'];

                if (isset($this->attributes[$name]) && $this->attributes[$name] !== '') {
                    return ImageStore::update($this->attributes[$name], $image);
                }

                return ImageStore::create($image);
            }
        );
    }

    /**
     * Преобразователь атрибута - получение: маленькое изображение.
     *
     * @param  mixed  $value  Значение атрибута.
     *
     * @return ImageEntity|null Маленькое изображение.
     */
    public function getImageSmallIdAttribute(mixed $value): ?ImageEntity
    {
        if (is_numeric($value) || is_string($value)) {
            return ImageStore::get(new RepositoryQueryBuilder($value));
        }

        return $value;
    }

    /**
     * Преобразователь атрибута - запись: среднее изображение.
     *
     * @param  mixed  $value  Значение атрибута.
     *
     * @return void
     * @throws FileNotFoundException|Exception
     */
    public function setImageMiddleIdAttribute(mixed $value): void
    {
        $name = 'image_middle_id';
        $folder = 'courses';

        $this->attributes[$name] = Image::set(
            $name,
            $value,
            function (string $name, UploadedFile $value) use ($folder) {
                if ($value->getClientOriginalExtension() === 'svg') {
                    $imageSvg = SVG::fromFile($value->path());
                    $imageRaster = $imageSvg->toRasterImage(500, 500);
                    $path = ImageStore::tmp('png');
                    imagepng($imageRaster, $path);

                    $image = Size::make($path);
                } else {
                    $path = ImageStore::tmp($value->getClientOriginalExtension());
                    $image = Size::make($value);
                }

                $image->resize(
                    500,
                    null,
                    function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    }
                )->save($path);

                $imageWebp = WebPConverter::createWebpImage($path, ['saveFile' => true]);

                ImageStore::setFolder($folder);
                $image = new ImageEntity();
                $image->path = $imageWebp['path'];

                if (isset($this->attributes[$name]) && $this->attributes[$name] !== '') {
                    return ImageStore::update($this->attributes[$name], $image);
                }

                return ImageStore::create($image);
            }
        );
    }

    /**
     * Преобразователь атрибута - получение: среднее изображение.
     *
     * @param  mixed  $value  Значение атрибута.
     *
     * @return ImageEntity|null Среднее изображение.
     */
    public function getImageMiddleIdAttribute(mixed $value): ?ImageEntity
    {
        if (is_numeric($value) || is_string($value)) {
            return ImageStore::get(new RepositoryQueryBuilder($value));
        }

        return $value;
    }

    /**
     * Преобразователь атрибута - запись: большое изображение.
     *
     * @param  mixed  $value  Значение атрибута.
     *
     * @return void
     * @throws FileNotFoundException|Exception
     */
    public function setImageBigIdAttribute(mixed $value): void
    {
        $name = 'image_big_id';
        $folder = 'courses';

        $this->attributes[$name] = Image::set(
            $name,
            $value,
            function (string $name, UploadedFile $value) use ($folder) {
                if ($value->getClientOriginalExtension() === 'svg') {
                    $imageSvg = SVG::fromFile($value->path());
                    $imageRaster = $imageSvg->toRasterImage(600, 600);
                    $path = ImageStore::tmp('png');
                    imagepng($imageRaster, $path);

                    $image = Size::make($path);
                } else {
                    $path = ImageStore::tmp($value->getClientOriginalExtension());
                    $image = Size::make($value);
                }

                $image->resize(
                    600,
                    null,
                    function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    }
                )->save($path);

                $imageWebp = WebPConverter::createWebpImage($path, ['saveFile' => true]);

                ImageStore::setFolder($folder);
                $image = new ImageEntity();
                $image->path = $imageWebp['path'];

                if (isset($this->attributes[$name]) && $this->attributes[$name] !== '') {
                    return ImageStore::update($this->attributes[$name], $image);
                }

                return ImageStore::create($image);
            }
        );
    }

    /**
     * Преобразователь атрибута - получение: большое изображение.
     *
     * @param  mixed  $value  Значение атрибута.
     *
     * @return ImageEntity|null Большое изображение.
     */
    public function getImageBigIdAttribute(mixed $value): ?ImageEntity
    {
        if (is_numeric($value) || is_string($value)) {
            return ImageStore::get(new RepositoryQueryBuilder($value));
        }

        return $value;
    }
}
