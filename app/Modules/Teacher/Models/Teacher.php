<?php
/**
 * Модуль Учителей.
 * Этот модуль содержит все классы для работы с учителями.
 *
 * @package App\Modules\Teacher
 */

namespace App\Modules\Teacher\Models;

use App\Modules\Analyzer\Models\Analyzer;
use App\Modules\Article\Models\Article;
use Exception;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Size;
use Eloquent;
use ImageStore;
use App\Models\Status;
use App\Models\Delete;
use App\Models\Validate;
use App\Models\Sortable;
use CodeBuds\WebPConverter\WebPConverter;
use EloquentFilter\Filterable;
use App\Modules\Image\Entities\Image as ImageEntity;
use App\Modules\Image\Helpers\Image;
use App\Modules\Metatag\Models\Metatag;
use Illuminate\Http\UploadedFile;
use App\Modules\Course\Models\Course;
use App\Modules\Direction\Models\Direction;
use App\Modules\School\Models\School;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Modules\Teacher\Database\Factories\TeacherFactory;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Modules\Teacher\Filters\TeacherFilter;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Класс модель для таблицы учителя на основе Eloquent.
 *
 * @property int|string $id ID учителя.
 * @property int|string $metatag_id ID метатегов.
 * @property string $name Название.
 * @property string|null $city Город.
 * @property string|null $comment Комментарий.
 * @property string $link Ссылка.
 * @property bool $copied Скопирован.
 * @property string $text Текст.
 * @property string $rating Рейтинг.
 * @property string $status Статус.
 * @property int|string|array|UploadedFile|ImageEntity $image_small_id Изображение маленькое.
 * @property int|string|array|UploadedFile|ImageEntity $image_middle_id Изображение среднее.
 * @property int|string|array|UploadedFile|ImageEntity $image_big_id Изображение большое.
 *
 * @property-read Metatag $metatag
 * @property-read Direction[] $directions
 * @property-read School[] $schools
 * @property-read Course[] $courses
 * @property-read TeacherExperience[] $experiences
 * @property-read TeacherSocialMedia[] $socialMedias
 * @property-read Article[] $articles
 * @property-read Analyzer[] $analyzers
 */
class Teacher extends Eloquent
{
    use Delete;
    use HasFactory;
    use Sortable;
    use SoftDeletes;
    use Status;
    use Validate;
    use Filterable;

    /**
     * Атрибуты, для которых разрешено массовое назначение.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'metatag_id',
        'name',
        'link',
        'copied',
        'city',
        'comment',
        'text',
        'rating',
        'status',
        'image_small_id',
        'image_middle_id',
        'image_big_id',
        'image_cropped_options',
    ];

    protected $casts = [
        'image_cropped_options' => 'array',
    ];

    /**
     * Метод, который должен вернуть все правила валидации.
     *
     * @return array Вернет массив правил.
     */
    protected function getRules(): array
    {
        return [
            'metatag_id' => 'digits_between:0,20',
            'name' => 'required|between:1,191',
            'link' => 'required|between:1,191|alpha_dash|unique_soft:teachers,link,'.$this->id.',id',
            'text' => 'max:65000',
            'city' => 'max:191',
            'comment' => 'max:191',
            'copied' => 'boolean',
            'rating' => 'nullable|float|float_between:0,5',
            'status' => 'required|boolean',
            'image_cropped_options' => 'nullable|json',
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
            'metatag_id' => trans('teacher::models.teacher.metatagId'),
            'name' => trans('teacher::models.teacher.name'),
            'link' => trans('teacher::models.teacher.link'),
            'copied' => trans('teacher::models.teacher.copied'),
            'city' => trans('teacher::models.teacher.city'),
            'comment' => trans('teacher::models.teacher.comment'),
            'text' => trans('teacher::models.teacher.text'),
            'image_small_id' => trans('teacher::models.teacher.imageSmallId'),
            'image_middle_id' => trans('teacher::models.teacher.imageMiddleId'),
            'image_cropped_options' => trans('teacher::models.teacher.imageCroppedOptions'),
            'image_big_id' => trans('teacher::models.teacher.imageBigId'),
            'rating' => trans('teacher::models.teacher.rating'),
            'status' => trans('teacher::models.teacher.status')
        ];
    }

    /**
     * Класс фильтр.
     *
     * @return string Название класса фильтра.
     */
    public function modelFilter(): string
    {
        return $this->provideFilter(TeacherFilter::class);
    }

    /**
     * Создание новой фабрики для модели.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return TeacherFactory::new();
    }

    /**
     * Преобразователь атрибута - запись: изображение маленькое.
     *
     * @param  mixed  $value  Значение атрибута.
     *
     * @return void
     * @throws FileNotFoundException|Exception
     */
    public function setImageSmallIdAttribute(mixed $value): void
    {
        $name = 'image_small_id';
        $folder = 'teachers';

        $this->attributes[$name] = Image::set(
            $name,
            $value,
            function (string $name, UploadedFile $value) use ($folder) {
                $path = ImageStore::tmp($value->getClientOriginalExtension());

                Size::make($value)->resize(
                    500,
                    null,
                    function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    }
                )->save($path);

                $imageWebp = $value->getClientOriginalExtension() !== 'webp'
                    ? WebPConverter::createWebpImage($path, ['saveFile' => true])
                    : ['path' => $path];

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
     * Преобразователь атрибута - получение: изображение логотипа.
     *
     * @param  mixed  $value  Значение атрибута.
     *
     * @return ImageEntity|null Маленькое изображение.
     */
    public function getImageSmallIdAttribute(mixed $value): ?ImageEntity
    {
        if (is_numeric($value) || is_string($value)) {
            return ImageStore::get($value);
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
        $folder = 'teachers';

        $this->attributes[$name] = Image::set(
            $name,
            $value,
            function (string $name, UploadedFile $value) use ($folder) {
                $path = ImageStore::tmp($value->getClientOriginalExtension());

                Size::make($value)->save($path);

                $imageWebp = $value->getClientOriginalExtension() !== 'webp'
                    ? WebPConverter::createWebpImage($path, ['saveFile' => true])
                    : ['path' => $path];

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
            return ImageStore::get($value);
        }

        return $value;
    }

    /**
     * Преобразователь атрибута - запись: больше изображение.
     *
     * @param  mixed  $value  Значение атрибута.
     *
     * @return void
     * @throws FileNotFoundException|Exception
     */
    public function setImageBigIdAttribute(mixed $value): void
    {
        $name = 'image_big_id';
        $folder = 'teachers';

        $this->attributes[$name] = Image::set(
            $name,
            $value,
            function (string $name, UploadedFile $value) use ($folder) {
                $path = ImageStore::tmp($value->getClientOriginalExtension());

                Size::make($value)->save($path);

                $imageWebp = $value->getClientOriginalExtension() !== 'webp'
                    ? WebPConverter::createWebpImage($path, ['saveFile' => true])
                    : ['path' => $path];

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
     * @return ImageEntity|null Среднее изображение.
     */
    public function getImageBigIdAttribute(mixed $value): ?ImageEntity
    {
        if (is_numeric($value) || is_string($value)) {
            return ImageStore::get($value);
        }

        return $value;
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
     * Направления этого учителя.
     *
     * @return BelongsToMany Модели направлений.
     */
    public function directions(): BelongsToMany
    {
        return $this->belongsToMany(Direction::class);
    }

    /**
     * Школы этого учителя.
     *
     * @return BelongsToMany Модели школ.
     */
    public function schools(): BelongsToMany
    {
        return $this->belongsToMany(School::class);
    }

    /**
     * Курсы этого учителя.
     *
     * @return BelongsToMany Модели курсов.
     */
    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class);
    }

    /**
     * Опыт работы учителя.
     *
     * @return HasMany Модели опыт работы учителя.
     */
    public function experiences(): HasMany
    {
        return $this->hasMany(TeacherExperience::class)->orderBy('weight', 'ASC');
    }

    /**
     * Социальные сети учителя.
     *
     * @return HasMany Модели социальных сетей учителя.
     */
    public function socialMedias(): HasMany
    {
        return $this->hasMany(TeacherSocialMedia::class)->orderBy('name', 'ASC');
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
     * Результаты анализа текста.
     *
     * @return MorphMany Модели анализа текста.
     */
    public function analyzers(): MorphMany
    {
        return $this->morphMany(Analyzer::class, 'analyzerable');
    }
}
