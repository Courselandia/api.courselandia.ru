<?php
/**
 * Модуль Коллекций.
 * Этот модуль содержит все классы для работы с коллекциями.
 *
 * @package App\Modules\Collection
 */

namespace App\Modules\Collection\Models;

use Eloquent;
use App\Models\Status;
use App\Models\Delete;
use App\Models\Validate;
use App\Models\Sortable;
use EloquentFilter\Filterable;
use App\Modules\Image\Entities\Image as ImageEntity;
use App\Modules\Metatag\Models\Metatag;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\UploadedFile;
use App\Modules\Course\Models\Course;
use App\Modules\Direction\Models\Direction;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Modules\Collection\Database\Factories\CollectionFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Modules\Collection\Filters\CollectionFilter;
use App\Modules\Collection\Models\CollectionFilter as CollectionFilterModel;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Modules\Analyzer\Models\Analyzer;
use App\Modules\Article\Models\Article;
use App\Modules\Collection\Images\ImageBig;
use App\Modules\Collection\Images\ImageMiddle;
use App\Modules\Collection\Images\ImageSmall;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Класс модель для таблицы коллекций на основе Eloquent.
 *
 * @property int|string $id ID коллекции.
 * @property int|string $metatag_id ID метатегов.
 * @property int|string $direction_id ID направления.
 * @property string $name Название.
 * @property string $link Ссылка.
 * @property string $text Текст.
 * @property string $additional Дополнительное описание.
 * @property int $amount Количество курсов.
 * @property string $sort_field Поле сортировки.
 * @property string $sort_direction Направление сортировки.
 * @property bool|null $copied Скопировано.
 * @property string $status Статус.
 * @property int|string|array|UploadedFile|ImageEntity $image_small_id Изображение маленькое.
 * @property int|string|array|UploadedFile|ImageEntity $image_middle_id Изображение среднее.
 * @property int|string|array|UploadedFile|ImageEntity $image_big_id Изображение большое.
 * @property array|null $image_small Изображение маленькое (нормализованное).
 * @property array|null $image_middle Изображение среднее (нормализованное).
 * @property array|null $image_big Изображение большое (нормализованное).
 *
 * @property-read Metatag $metatag
 * @property-read Direction $direction
 * @property-read Course[] $courses
 * @property-read CollectionFilterModel[] $filters
 * @property-read Article[] $articles
 * @property-read Analyzer[] $analyzers
 */
class Collection extends Eloquent
{
    use Delete;
    use HasFactory;
    use Sortable;
    use SoftDeletes;
    use Status;
    use Validate;
    use Filterable;
    use HasTimestamps;

    /**
     * Атрибуты, для которых разрешено массовое назначение.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'metatag_id',
        'direction_id',
        'name',
        'link',
        'text',
        'additional',
        'amount',
        'sort_field',
        'sort_direction',
        'copied',
        'image_small_id',
        'image_middle_id',
        'image_big_id',
        'image_small',
        'image_middle',
        'image_big',
        'status',
    ];

    protected $casts = [
        'image_small_id' => ImageSmall::class,
        'image_middle_id' => ImageMiddle::class,
        'image_big_id' => ImageBig::class,
        'image_small' => 'array',
        'image_middle' => 'array',
        'image_big' => 'array',
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
            'direction_id' => 'required|digits_between:0,20',
            'name' => 'required|between:1,191',
            'link' => 'required|between:1,191|alpha_dash|unique_soft:collections,link,' . $this->id . ',id',
            'text' => 'max:65000',
            'additional' => 'max:65000',
            'amount' => 'required|digits_between:0,5',
            'sort_field' => 'required|max:25',
            'sort_direction' => 'required|max:4',
            'copied' => 'boolean',
            'image_small' => 'nullable|json',
            'image_middle' => 'nullable|json',
            'image_big' => 'nullable|json',
            'status' => 'required|boolean',
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
            'metatag_id' => trans('collection::models.collection.metatagId'),
            'name' => trans('collection::models.collection.name'),
            'link' => trans('collection::models.collection.link'),
            'text' => trans('collection::models.collection.text'),
            'additional' => trans('collection::models.collection.additional'),
            'image_small_id' => trans('collection::models.collection.imageSmallId'),
            'image_middle_id' => trans('collection::models.collection.imageMiddleId'),
            'image_cropped_options' => trans('collection::models.collection.imageCroppedOptions'),
            'image_big_id' => trans('collection::models.collection.imageBigId'),
            'amount' => trans('collection::models.collection.amount'),
            'sort_field' => trans('collection::models.collection.sortField'),
            'sort_direction' => trans('collection::models.collection.sortDirection'),
            'copied' => trans('collection::models.collection.copied'),
            'image_small' => trans('collection::models.collection.imageSmallId'),
            'image_middle' => trans('collection::models.collection.imageMiddleId'),
            'image_big' => trans('collection::models.collection.imageBigId'),
            'status' => trans('collection::models.collection.status'),
        ];
    }

    /**
     * Класс фильтр.
     *
     * @return string Название класса фильтра.
     */
    public function modelFilter(): string
    {
        return $this->provideFilter(CollectionFilter::class);
    }

    /**
     * Создание новой фабрики для модели.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return CollectionFactory::new();
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
     * @return BelongsTo Модель направления.
     */
    public function direction(): BelongsTo
    {
        return $this->belongsTo(Direction::class);
    }

    /**
     * Фильтры коллекции.
     *
     * @return HasMany Модели фильтров.
     */
    public function filters(): HasMany
    {
        return $this->hasMany(CollectionFilterModel::class);
    }

    /**
     * Курсы этого учителя.
     *
     * @return BelongsToMany Модели курсов.
     */
    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'collection_course');
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
