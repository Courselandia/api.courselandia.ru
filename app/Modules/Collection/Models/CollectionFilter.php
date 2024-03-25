<?php
/**
 * Модуль Коллекций.
 * Этот модуль содержит все классы для работы с коллекциями.
 *
 * @package App\Modules\Collection
 */

namespace App\Modules\Collection\Models;

use Eloquent;
use App\Models\Delete;
use App\Models\Validate;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use App\Modules\Collection\Database\Factories\CollectionFilterFactory;

/**
 * Класс модель для таблицы коллекций на основе Eloquent.
 *
 * @property int|string $id ID коллекции.
 * @property int|string $collection_id ID коллекции.
 * @property string $name Название.
 * @property string $value Значение.
 *
 * @property-read Collection $collection
 */
class CollectionFilter extends Eloquent
{
    use Delete;
    use HasFactory;
    use SoftDeletes;
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
        'collection_id',
        'name',
        'value',
    ];

    /**
     * Метод, который должен вернуть все правила валидации.
     *
     * @return array Вернет массив правил.
     */
    protected function getRules(): array
    {
        return [
            'collection_id' => 'required|digits_between:0,20',
            'name' => 'required|between:1,191',
            'value' => 'required|between:1,191',
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
            'collection_id' => trans('collection::models.collectionFilter.collectionId'),
            'name' => trans('collection::models.collectionFilter.name'),
            'value' => trans('collection::models.collectionFilter.value'),
        ];
    }

    /**
     * Создание новой фабрики для модели.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return CollectionFilterFactory::new();
    }

    /**
     * Коллекция.
     *
     * @return BelongsTo Модель коллекции.
     */
    public function collection(): BelongsTo
    {
        return $this->belongsTo(Collection::class);
    }
}
