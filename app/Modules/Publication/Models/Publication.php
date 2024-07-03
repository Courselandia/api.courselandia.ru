<?php
/**
 * Модуль Публикации.
 * Этот модуль содержит все классы для работы с публикациями.
 *
 * @package App\Modules\Publication
 */

namespace App\Modules\Publication\Models;

use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Eloquent;
use Carbon\Carbon;
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
use App\Modules\Publication\Database\Factories\PublicationFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Modules\Publication\Filters\PublicationFilter;
use App\Modules\Publication\Images\ImageBig;
use App\Modules\Publication\Images\ImageMiddle;
use App\Modules\Publication\Images\ImageSmall;

/**
 * Класс модель для таблицы публикаций на основе Eloquent.
 *
 * @property int|string $id ID публикации.
 * @property int|string $metatag_id ID метатегов.
 * @property Carbon $published_at Дата добавления.
 * @property string $header Заголовок статьи.
 * @property string $link Ссылка на статью.
 * @property string $anons Анонс.
 * @property string $article Текст статьи.
 * @property string $status Статус.
 * @property int|string|array|UploadedFile|ImageEntity $image_big_id Большое изображение.
 * @property int|string|array|UploadedFile|ImageEntity $image_middle_id Среднее изображение.
 * @property int|string|array|UploadedFile|ImageEntity $image_small_id Маленькое изображение.
 * @property array|null $image_small Изображение маленькое (нормализованное).
 * @property array|null $image_middle Изображение среднее (нормализованное).
 * @property array|null $image_big Изображение большое (нормализованное).
 *
 * @property-read Metatag $metatag
 */
class Publication extends Eloquent
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
        'published_at',
        'header',
        'link',
        'anons',
        'article',
        'status',
        'image_big_id',
        'image_middle_id',
        'image_small_id',
        'image_small',
        'image_middle',
        'image_big',
    ];

    /**
     * Типизирование атрибутов.
     *
     * @var array
     */
    protected $casts = [
        'image_small_id' => ImageSmall::class,
        'image_middle_id' => ImageMiddle::class,
        'image_big_id' => ImageBig::class,
        'published_at' => 'datetime',
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
            'published_at' => 'required|date',
            'header' => 'required|between:1,191',
            'link' => 'required|between:1,191|alpha_dash|unique_soft:publications,link,' . $this->id . ',id',
            'anons' => 'max:1000',
            'article' => 'max:16000000',
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
            'metatag_id' => trans('publication::models.publication.metatagId'),
            'published_at' => trans('publication::models.publication.publishedAt'),
            'header' => trans('publication::models.publication.header'),
            'link' => trans('publication::models.publication.link'),
            'anons' => trans('publication::models.publication.anons'),
            'article' => trans('publication::models.publication.article'),
            'image_big_id' => trans('publication::models.publication.imageBigId'),
            'image_middle_id' => trans('publication::models.publication.imageMiddleId'),
            'image_small_id' => trans('publication::models.publication.imageSmallId'),
            'image_small' => trans('teacher::models.teacher.imageSmallId'),
            'image_middle' => trans('publication::models.publication.imageMiddleId'),
            'image_big' => trans('publication::models.publication.imageBigId'),
            'status' => trans('publication::models.publication.status'),
        ];
    }

    /**
     * Класс фильтр.
     *
     * @return string Название класса фильтра.
     */
    public function modelFilter(): string
    {
        return $this->provideFilter(PublicationFilter::class);
    }

    /**
     * Создание новой фабрики для модели.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return PublicationFactory::new();
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
     * Заготовка запроса для указания года.
     *
     * @param Builder $query Запрос.
     * @param int|null $year Год.
     *
     * @return Builder Построитель запросов.
     */
    public function scopeYear(Builder $query, ?int $year = null): Builder
    {
        return $year ? $query->where(DB::raw("DATE_FORMAT(published_at, '%Y')"), $year) : $query;
    }

    /**
     * Заготовка запроса для указания ссылки.
     *
     * @param Builder $query Запрос.
     * @param string|null $link Ссылка.
     *
     * @return Builder Построитель запросов.
     */
    public function scopeLink(Builder $query, ?string $link = null): Builder
    {
        return $link ? $query->where('link', $link) : $query;
    }

    /**
     * Заготовка запроса для указания ID.
     *
     * @param Builder $query Запрос.
     * @param int|null $id ID.
     *
     * @return Builder Построитель запросов.
     */
    public function scopeId(Builder $query, ?int $id = null): Builder
    {
        return $id ? $query->where('id', $id) : $query;
    }
}
