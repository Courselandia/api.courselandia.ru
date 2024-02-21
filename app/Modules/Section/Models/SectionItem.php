<?php
/**
 * Модуль Разделов.
 * Этот модуль содержит все классы для работы с разделами каталога.
 *
 * @package App\Modules\Section
 */

namespace App\Modules\Section\Models;

use App\Modules\Section\Filters\SectionItemFilter;
use Eloquent;
use App\Models\Status;
use App\Models\Delete;
use App\Models\Validate;
use App\Models\Sortable;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Modules\Section\Database\Factories\SectionItemFactory;

/**
 * Класс модель для таблицы элементов раздела на основе Eloquent.
 *
 * @property int|string $id ID элемента раздела.
 * @property int|string $section_id ID раздела.
 * @property int $weight Вес элемента.
 * @property int|string $itemable_id ID сущности для элемента.
 * @property int|string $itemable_type Имя класса сущности для элемента.
 *
 * @property-read Eloquent $itemable
 */
class SectionItem extends Eloquent
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
        'section_id',
        'weight',
        'itemable_id',
        'itemable_type',
    ];

    /**
     * Метод, который должен вернуть все правила валидации.
     *
     * @return array Вернет массив правил.
     */
    protected function getRules(): array
    {
        return [
            'section_id' => 'required|digits_between:0,20',
            'weight' => 'required|digits_between:0,20',
            'itemable_id' => 'nullable|digits_between:0,20',
            'itemable_type' => 'nullable|between:1,191',
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
            'section_id' => trans('section::models.sectionItem.sectionId'),
            'weight' => trans('section::models.sectionItem.weight'),
            'itemable_id' => trans('section::models.sectionItem.itemableId'),
            'itemable_type' => trans('section::models.sectionItem.itemableType'),
        ];
    }

    /**
     * Класс фильтр.
     *
     * @return string Название класса фильтра.
     */
    public function modelFilter(): string
    {
        return $this->provideFilter(SectionItemFilter::class);
    }

    /**
     * Создание новой фабрики для модели.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return SectionItemFactory::new();
    }

    /**
     * Получить все модели, обладающие itemable.
     *
     * @return MorphTo Вернет модель элемента.
     */
    public function itemable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Получить раздел элемента.
     *
     * @return BelongsTo Раздел.
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }
}
