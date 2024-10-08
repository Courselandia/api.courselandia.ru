<?php
/**
 * Модуль Изображения.
 * Этот модуль содержит все классы для работы с изображениями которые хранятся к записям в базе данных.
 *
 * @package App\Modules\Image
 */

namespace App\Modules\Image\Models;

use App\Models\Sortable;
use App\Modules\Image\Filters\ImageFilter;
use Eloquent;
use App\Models\Delete;
use App\Models\Validate;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Класс модель для таблицы изображений на основе Eloquent.
 *
 * @property int|string $id ID изображения.
 * @property int $format Формат изображения.
 * @property int $folder Папка.
 * @property mixed $byte Байт код изображения.
 * @property string $cache Предиката для кеширования.
 * @property int $width Ширина изображения.
 * @property int $height Высота изображения.
 * @property ?string $path Путь к изображению.
 * @property ?string $pathCache Путь к изображению с кешированием.
 * @property ?string $pathSource Путь к фактическому местоположению изображения.
 */
class ImageEloquent extends Eloquent
{
    use Delete;
    use Sortable;
    use SoftDeletes;
    use Validate;
    use Filterable;
    use HasTimestamps;

    /**
     * Название таблицы базы данных.
     *
     * @var string
     */
    protected $table = 'images';

    /**
     * Параметр для хранения пути к файлу.
     *
     * @var string
     */
    public string $path;

    /**
     * Параметр для хранения пути к файлу без кеш предикат.
     *
     * @var string
     */
    public string $pathCache;

    /**
     * Параметр для хранения физического пути к файлу.
     *
     * @var string
     */
    public string $pathSource;

    /**
     * Расширенные пользовательские события.
     *
     * @var array
     */
    protected $observables = ['readed'];

    /**
     * Атрибуты, для которых разрешено массовое назначение.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'byte',
        'folder',
        'format',
        'cache',
        'width',
        'height'
    ];

    /**
     * Метод, который должен вернуть все правила валидации.
     *
     * @return array Вернет массив правил.
     */
    protected function getRules(): array
    {
        return [
            'format' => 'required|between:1,20',
            'folder' => 'required|between:1,191',
            'cache' => 'max:50',
            'width' => 'integer|digits_between:1,5',
            'height' => 'integer|digits_between:1,5'
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
            'byte' => trans('image::models.image.byte'),
            'folder' => trans('image::models.image.folder'),
            'format' => trans('image::models.image.format'),
            'cache' => trans('image::models.image.cache'),
            'width' => trans('image::models.image.width'),
            'height' => trans('image::models.image.height')
        ];
    }

    /**
     * Класс фильтр.
     *
     * @return string Название класса фильтра.
     */
    public function modelFilter(): string
    {
        return $this->provideFilter(ImageFilter::class);
    }

    /**
     * Перегружаем стандартный метод для возможности запуска события на чтение.
     *
     * @param  array  $attributes  Значения атрибутов.
     * @param  bool  $sync  Синхронизировать.
     *
     * @return void
     */
    public function setRawAttributes(array $attributes, $sync = false): void
    {
        parent::setRawAttributes($attributes, $sync);
        $this->fireModelEvent('readed');
    }
}
