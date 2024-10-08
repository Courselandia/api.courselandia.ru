<?php
/**
 * Модуль Документов.
 * Этот модуль содержит все классы для работы с документами которые хранятся к записям в базе данных.
 *
 * @package App\Modules\Document
 */

namespace App\Modules\Document\Models;

use App\Models\Sortable;
use App\Modules\Document\Filters\DocumentFilter;
use Eloquent;
use App\Models\Delete;
use App\Models\Validate;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Класс модель для таблицы документов на основе Eloquent.
 *
 * @property int|string $id ID документа.
 * @property int $format Формат документа.
 * @property int $folder Папка.
 * @property mixed $byte Байт код документа.
 * @property string $cache Предиката для кеширования.
 * @property ?string $path Путь к документу.
 * @property ?string $pathCache Путь к документу с кешированием.
 * @property ?string $pathSource Путь к фактическому местоположению документа.
 */
class DocumentEloquent extends Eloquent
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
    protected $table = 'documents';

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
            'byte' => trans('document::models.document.byte'),
            'folder' => trans('document::models.document.folder'),
            'format' => trans('document::models.document.format'),
            'cache' => trans('document::models.document.cache'),
        ];
    }

    /**
     * Класс фильтр.
     *
     * @return string Название класса фильтра.
     */
    public function modelFilter(): string
    {
        return $this->provideFilter(DocumentFilter::class);
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
