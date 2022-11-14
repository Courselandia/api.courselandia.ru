<?php
/**
 * Модуль Учителей.
 * Этот модуль содержит все классы для работы с учителями.
 *
 * @package App\Modules\Teacher
 */

namespace App\Modules\Teacher\Models;

use App\Modules\Course\Models\Course;
use App\Modules\Direction\Models\Direction;
use App\Modules\School\Models\School;
use Exception;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
use App\Modules\Teacher\Database\Factories\TeacherFactory;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Modules\Teacher\Filters\TeacherFilter;

/**
 * Класс модель для таблицы учителя на основе Eloquent.
 *
 * @property int|string $id ID учителя.
 * @property int|string $metatag_id ID метатегов.
 * @property string $name Название.
 * @property string $link Ссылка.
 * @property string $text Текст.
 * @property string $rating Рейтинг.
 * @property string $status Статус.
 * @property int|string|array|UploadedFile|ImageEntity $image_small_id Изображение маленькое.
 * @property int|string|array|UploadedFile|ImageEntity $image_middle_id Изображение среднее.
 *
 * @property-read Metatag $metatag
 * @property-read Direction[] $directions
 * @property-read School[] $schools
 * @property-read Course[] $courses
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
        'text',
        'rating',
        'status',
        'image_small_id',
        'image_middle_id',
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
        'link' => 'string',
        'text' => 'string',
        'rating' => 'string',
        'status' => 'string',
    ])] protected function getRules(): array
    {
        return [
            'metatag_id' => 'digits_between:0,20',
            'name' => 'required|between:1,191',
            'link' => 'required|between:1,191|alpha_dash|unique_soft:teachers,link,'.$this->id.',id',
            'text' => 'max:65000',
            'rating' => 'nullable|float|float_between:0,5',
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
            'metatag_id' => trans('teacher::models.teacher.metatagId'),
            'name' => trans('teacher::models.teacher.name'),
            'link' => trans('teacher::models.teacher.link'),
            'text' => trans('teacher::models.teacher.text'),
            'image_small_id' => trans('teacher::models.teacher.imageSmallId'),
            'image_middle_id' => trans('teacher::models.teacher.imageMiddleId'),
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

                Size::make($value)->fit(150, 150)->save($path);

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
}
