<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Models;

use SVG\SVG;
use App\Modules\Course\Enums\Duration;
use App\Modules\Course\Enums\Language;
use App\Modules\Metatag\Models\Metatag;
use App\Modules\Review\Models\Review;
use Exception;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Size;
use ImageStore;
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
use App\Modules\Employment\Models\Employment;
use CodeBuds\WebPConverter\WebPConverter;
use Eloquent;
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
 * @property string $header Заголовок.
 * @property string $text Описание.
 * @property string $header_morphy Заголовок морфологизированное.
 * @property string $text_morphy Описание морфологизированное.
 * @property string $link Ссылка.
 * @property string $url URL на курс.
 * @property string $language Язык курса.
 * @property float $rating Рейтинг.
 * @property float $price Цена.
 * @property float $price_old Старая цена.
 * @property float $price_recurrent_price Цена по кредиту.
 * @property string $currency Валюта.
 * @property boolean $online Онлайн.
 * @property boolean $employment С трудоустройством.
 * @property int $duration Продолжительность.
 * @property float $duration_rate Рейт продолжительность.
 * @property string $duration_unit Единица измерения продолжительности.
 * @property int $lessons_amount Количество уроков.
 * @property int $modules_amount Количество модулей.
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
 * @property-read Employment[] $employments
 * @property-read CourseLevel[] $levels
 * @property-read CourseLearn[] $learns
 * @property-read CourseFeature[] $features
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
        'header',
        'text',
        'header_morphy',
        'text_morphy',
        'link',
        'url',
        'language',
        'rating',
        'price',
        'price_old',
        'price_recurrent_price',
        'currency',
        'online',
        'employment',
        'duration',
        'duration_rate',
        'duration_unit',
        'lessons_amount',
        'modules_amount',
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
        'header' => 'string',
        'text' => 'string',
        'header_morphy' => 'string',
        'text_morphy' => 'string',
        'link' => 'string',
        'url' => 'string',
        'language' => 'string',
        'rating' => 'string',
        'price' => 'string',
        'price_old' => 'string',
        'price_recurrent_price' => 'string',
        'currency' => 'string',
        'online' => 'string',
        'employment' => 'string',
        'duration' => 'string',
        'duration_rate' => 'string',
        'duration_unit' => 'string',
        'lessons_amount' => 'string',
        'modules_amount' => 'string',
        'status' => 'string',
    ])] protected function getRules(): array
    {
        return [
            'uuid' => 'max:191',
            'metatag_id' => 'digits_between:0,20',
            'school_id' => 'required|digits_between:0,20|exists_soft:schools,id',
            'header' => 'required|between:1,191',
            'text' => 'max:5000',
            'header_morphy' => 'max:191',
            'text_morphy' => 'max:5000',
            'link' => 'required|between:1,191|alpha_dash',
            'url' => 'required|url',
            'language' => 'in:' . implode(',', EnumList::getValues(Language::class)),
            'rating' => 'nullable|float|float_between:0,5',
            'price' => 'nullable|float|float_between:0,999999',
            'price_old' => 'nullable|float|float_between:0,999999',
            'price_recurrent_price' => 'nullable|float|float_between:0,999999',
            'currency' => 'in:' . implode(',', EnumList::getValues(Currency::class)),
            'online' => 'boolean',
            'employment' => 'boolean',
            'duration' => 'integer|digits_between:0,5',
            'duration_rate' => 'nullable|float',
            'duration_unit' => 'in:' . implode(',', EnumList::getValues(Duration::class)),
            'lessons_amount' => 'integer|digits_between:0,5',
            'modules_amount' => 'integer|digits_between:0,5',
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
            'header' => trans('course::models.course.header'),
            'text' => trans('course::models.course.text'),
            'header_morphy' => trans('course::models.course.headerMorphy'),
            'text_morphy' => trans('course::models.course.textMorphy'),
            'link' => trans('course::models.course.link'),
            'url' => trans('course::models.course.url'),
            'language' => trans('course::models.course.language'),
            'rating' => trans('course::models.course.rating'),
            'price' => trans('course::models.course.price'),
            'price_old' => trans('course::models.course.priceOld'),
            'price_recurrent_price' => trans('course::models.course.priceRecurrentPrice'),
            'currency' => trans('course::models.course.currency'),
            'online' => trans('course::models.course.online'),
            'employment' => trans('course::models.course.employment'),
            'duration' => trans('course::models.course.duration'),
            'duration_rate' => trans('course::models.course.durationRate'),
            'duration_unit' => trans('course::models.course.durationUnit'),
            'lessons_amount' => trans('course::models.course.lessonsAmount'),
            'modules_amount' => trans('course::models.course.modulesAmount'),
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
                    $imageRaster = $imageSvg->toRasterImage(600, 600);
                    $path = ImageStore::tmp('png');
                    imagepng($imageRaster, $path);

                    $image = Size::make($path);
                } else {
                    $path = ImageStore::tmp($value->getClientOriginalExtension());
                    $image = Size::make($value);
                }

                $image->resize(
                    350,
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
