<?php
/**
 * Модуль Публикации.
 * Этот модуль содержит все классы для работы с публикациями.
 *
 * @package App\Modules\Publication
 */

namespace App\Modules\Publication\Models;

use DB;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Size;
use Eloquent;
use ImageStore;
use Carbon\Carbon;
use App\Models\Status;
use App\Models\Delete;
use App\Models\Validate;
use App\Models\Sortable;
use CodeBuds\WebPConverter\WebPConverter;
use EloquentFilter\Filterable;
use App\Models\Rep\RepositoryQueryBuilder;
use App\Modules\Image\Entities\Image as ImageEntity;
use App\Modules\Image\Helpers\Image;
use App\Modules\Metatag\Models\Metatag;
use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Modules\Publication\Database\Factories\PublicationFactory;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Modules\Publication\Filters\PublicationFilter;

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

    /**
     * Атрибуты, которые должны быть преобразованы к дате.
     *
     * @var array
     */
    protected $dates = [
        'published_at'
    ];

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
        'image_small_id'
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
            'article' => 'max:65000',
            'status' => 'required|boolean'
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
            'status' => trans('publication::models.publication.status')
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
     * Преобразователь атрибута - запись: маленькое изображение.
     *
     * @param mixed $value Значение атрибута.
     *
     * @return void
     * @throws FileNotFoundException|Exception
     */
    public function setImageSmallIdAttribute(mixed $value): void
    {
        $name = 'image_small_id';
        $folder = 'publications';

        $this->attributes[$name] = Image::set(
            $name,
            $value,
            function (string $name, UploadedFile $value) use ($folder) {
                $path = ImageStore::tmp($value->getClientOriginalExtension());

                Size::make($value)->fit(150, 150)->save($path);

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
     * Преобразователь атрибута - получение: маленькое изображение.
     *
     * @param mixed $value Значение атрибута.
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
     * @param mixed $value Значение атрибута.
     *
     * @return void
     * @throws FileNotFoundException|Exception
     */
    public function setImageMiddleIdAttribute(mixed $value): void
    {
        $name = 'image_middle_id';
        $folder = 'publications';

        $this->attributes[$name] = Image::set(
            $name,
            $value,
            function (string $name, UploadedFile $value) use ($folder) {
                $path = ImageStore::tmp($value->getClientOriginalExtension());

                Size::make($value)->resize(
                    400,
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
     * Преобразователь атрибута - получение: среднее изображение.
     *
     * @param mixed $value Значение атрибута.
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
     * @param mixed $value Значение атрибута.
     *
     * @return void
     * @throws FileNotFoundException|Exception
     */
    public function setImageBigIdAttribute(mixed $value): void
    {
        $name = 'image_big_id';
        $folder = 'publications';

        $this->attributes[$name] = Image::set(
            $name,
            $value,
            function (string $name, UploadedFile $value) use ($folder) {
                $path = ImageStore::tmp($value->getClientOriginalExtension());

                Size::make($value)->resize(
                    1200,
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
     * Преобразователь атрибута - получение: большое изображение.
     *
     * @param mixed $value Значение атрибута.
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
