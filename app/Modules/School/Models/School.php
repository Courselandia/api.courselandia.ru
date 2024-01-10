<?php
/**
 * Модуль Школ.
 * Этот модуль содержит все классы для работы со школами.
 *
 * @package App\Modules\School
 */

namespace App\Modules\School\Models;

use App\Modules\Analyzer\Models\Analyzer;
use App\Modules\Article\Models\Article;
use App\Modules\Course\Models\Course;
use App\Modules\Faq\Models\Faq;
use App\Modules\Review\Models\Review;
use App\Modules\School\Images\ImageLogo;
use App\Modules\School\Images\ImageSite;
use App\Modules\Teacher\Models\Teacher;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Eloquent;
use App\Models\Status;
use App\Models\Delete;
use App\Models\Validate;
use App\Models\Sortable;
use EloquentFilter\Filterable;
use App\Modules\Image\Entities\Image as ImageEntity;
use App\Modules\Metatag\Models\Metatag;
use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Modules\School\Database\Factories\SchoolFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Modules\School\Filters\SchoolFilter;

/**
 * Класс модель для таблицы школ на основе Eloquent.
 *
 * @property int|string $id ID школы.
 * @property int|string $metatag_id ID метатегов.
 * @property string $name Название.
 * @property string $header Заголовок статьи.
 * @property string $header_template Шаблон заголовок.
 * @property string $link Ссылка на статью.
 * @property string $text Текст.
 * @property string $rating Рейтинг.
 * @property string $site Ссылка на сайт.
 * @property string $status Статус.
 * @property int|string|array|UploadedFile|ImageEntity $image_site_id Изображение сайта.
 * @property int|string|array|UploadedFile|ImageEntity $image_logo_id Изображение логотипа.
 * @property array $amount_courses Статистика количества курсов.
 *
 * @property-read Metatag $metatag
 * @property-read Teacher[] $teachers
 * @property-read Review[] $reviews
 * @property-read Faq[] $faqs
 * @property-read Course[] $courses
 * @property-read Article[] $articles
 * @property-read Analyzer[] $analyzers
 */
class School extends Eloquent
{
    use Delete;
    use HasFactory;
    use Sortable;
    use SoftDeletes;
    use Status;
    use Validate;
    use Filterable;

    /**
     * Типизирование атрибутов.
     *
     * @var array
     */
    protected $casts = [
        'amount_courses' => 'array',
        'image_logo_id' => ImageLogo::class,
        'image_site_id' => ImageSite::class,
    ];

    /**
     * Атрибуты, для которых разрешено массовое назначение.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'metatag_id',
        'name',
        'header',
        'header_template',
        'link',
        'text',
        'rating',
        'site',
        'status',
        'image_logo_id',
        'image_site_id',
        'amount_courses',
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
            'header' => 'max:191',
            'header_template' => 'max:191',
            'link' => 'required|between:1,191|alpha_dash|unique_soft:schools,link,'.$this->id.',id',
            'text' => 'max:65000',
            'rating' => 'nullable|float|float_between:0,5',
            'site' => 'url',
            'status' => 'required|boolean',
            'amount_courses' => 'json',
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
            'metatag_id' => trans('school::models.school.metatagId'),
            'name' => trans('school::models.school.name'),
            'header' => trans('school::models.school.header'),
            'header_template' => trans('school::models.school.headerTemplate'),
            'link' => trans('school::models.school.link'),
            'text' => trans('school::models.school.text'),
            'image_logo_id' => trans('school::models.school.imageLogoId'),
            'image_site_id' => trans('school::models.school.imageSiteId'),
            'rating' => trans('school::models.school.rating'),
            'site' => trans('school::models.school.site'),
            'status' => trans('school::models.school.status'),
            'amount_courses' => trans('school::models.school.amountCourses'),
        ];
    }

    /**
     * Класс фильтр.
     *
     * @return string Название класса фильтра.
     */
    public function modelFilter(): string
    {
        return $this->provideFilter(SchoolFilter::class);
    }

    /**
     * Создание новой фабрики для модели.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return SchoolFactory::new();
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
     * Учителя этой школы.
     *
     * @return BelongsToMany Модели учителей.
     */
    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(Teacher::class);
    }

    /**
     * Отзывы этой школы.
     *
     * @return HasMany Модели отзывов.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * FAQ's этой школы.
     *
     * @return HasMany Модели FAQ's.
     */
    public function faqs(): HasMany
    {
        return $this->hasMany(Faq::class);
    }

    /**
     * Курсы этой школы.
     *
     * @return HasMany Модели курсов.
     */
    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
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
