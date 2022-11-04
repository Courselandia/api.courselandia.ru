<?php
/**
 * Модуль Школ.
 * Этот модуль содержит все классы для работы со школами.
 *
 * @package App\Modules\School
 */

namespace App\Modules\School\Models;

use App\Modules\Review\Models\Review;
use App\Modules\Teacher\Models\Teacher;
use Exception;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Size;
use Eloquent;
use ImageStore;
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
use JetBrains\PhpStorm\ArrayShape;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Modules\School\Database\Factories\SchoolFactory;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Modules\School\Filters\SchoolFilter;

/**
 * Класс модель для таблицы школ на основе Eloquent.
 *
 * @property int|string $id ID школы.
 * @property int|string $metatag_id ID метатегов.
 * @property string $name Название.
 * @property string $header Заголовок статьи.
 * @property string $link Ссылка на статью.
 * @property string $text Текст.
 * @property string $rating Рейтинг.
 * @property string $site Ссылка на сайт.
 * @property string $status Статус.
 * @property int|string|array|UploadedFile|ImageEntity $image_site_id Изображение сайта.
 * @property int|string|array|UploadedFile|ImageEntity $image_logo_id Изображение логотипа.
 *
 * @property-read Metatag $metatag
 * @property-read Teacher[] $teachers
 * @property-read Review[] $reviews
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
     * Атрибуты, для которых разрешено массовое назначение.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'metatag_id',
        'name',
        'header',
        'link',
        'text',
        'rating',
        'site',
        'status',
        'image_logo_id',
        'image_site_id',
    ];

    /**
     * Метод, который должен вернуть все правила валидации.
     *
     * @return array Вернет массив правил.
     */
    #[ArrayShape([
        'id' => 'string',
        'metatag_id' => 'string',
        'name' => 'string',
        'header' => 'string',
        'link' => 'string',
        'text' => 'string',
        'rating' => 'string',
        'site' => 'string',
        'status' => 'string',
        'image_logo_id' => 'string',
        'image_site_id' => 'string',
    ])] protected function getRules(): array
    {
        return [
            'metatag_id' => 'digits_between:0,20',
            'name' => 'required|between:1,191',
            'header' => 'required|between:1,191',
            'link' => 'required|between:1,191|alpha_dash|unique_soft:schools,link,'.$this->id.',id',
            'text' => 'max:65000',
            'rating' => 'float|float_between:0,5',
            'site' => 'url',
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
            'metatag_id' => trans('school::models.school.metatagId'),
            'name' => trans('school::models.school.name'),
            'header' => trans('school::models.school.header'),
            'link' => trans('school::models.school.link'),
            'text' => trans('school::models.school.text'),
            'image_logo_id' => trans('school::models.school.imageLogoId'),
            'image_site_id' => trans('school::models.school.imageSiteId'),
            'rating' => trans('school::models.school.rating'),
            'site' => trans('school::models.school.site'),
            'status' => trans('school::models.school.status')
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
     * Преобразователь атрибута - запись: изображение логотипа.
     *
     * @param  mixed  $value  Значение атрибута.
     *
     * @return void
     * @throws FileNotFoundException|Exception
     */
    public function setImageLogoIdAttribute(mixed $value): void
    {
        $name = 'image_logo_id';
        $folder = 'schools';

        $this->attributes[$name] = Image::set(
            $name,
            $value,
            function (string $name, UploadedFile $value) use ($folder) {
                $path = ImageStore::tmp($value->getClientOriginalExtension());

                Size::make($value)->resize(
                    150,
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
     * Преобразователь атрибута - получение: изображение логотипа.
     *
     * @param  mixed  $value  Значение атрибута.
     *
     * @return ImageEntity|null Маленькое изображение.
     */
    public function getImageLogoIdAttribute(mixed $value): ?ImageEntity
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
    public function setImageSiteIdAttribute(mixed $value): void
    {
        $name = 'image_site_id';
        $folder = 'schools';

        $this->attributes[$name] = Image::set(
            $name,
            $value,
            function (string $name, UploadedFile $value) use ($folder) {
                $path = ImageStore::tmp($value->getClientOriginalExtension());

                Size::make($value)->resize(
                    800,
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
    public function getImageSiteIdAttribute(mixed $value): ?ImageEntity
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
}
