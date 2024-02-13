<?php
/**
 * Модуль Документов.
 * Этот модуль содержит все классы для работы с документами которые хранятся к записям в базе данных.
 *
 * @package App\Modules\Document
 */

namespace App\Modules\Document\Models;

use MongoDb;
use App\Models\Validate;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Класс модель для таблицы документов на основе MongoDb.
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
class DocumentMongoDb extends MongoDb
{
    use SoftDeletes;
    use Validate;

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
     * Связанная с моделью таблица.
     *
     * @var string
     */
    protected $collection = 'documents';

    /**
     * Тип соединения.
     *
     * @var string
     */
    protected $connection = 'mongodb';

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
            'folder' => 'required|between:1,191',
            'format' => 'required|between:1,20',
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
