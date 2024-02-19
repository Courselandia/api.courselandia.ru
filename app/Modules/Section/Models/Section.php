<?php
/**
 * Модуль Разделов.
 * Этот модуль содержит все классы для работы с разделами каталога.
 *
 * @package App\Modules\Section
 */

namespace App\Modules\Section\Models;

use Eloquent;
use App\Models\Status;
use App\Models\Delete;
use App\Models\Validate;
use App\Models\Sortable;
use EloquentFilter\Filterable;
use App\Modules\Metatag\Models\Metatag;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Modules\Section\Database\Factories\SectionFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Modules\Section\Filters\SectionFilter;
use App\Models\Enums\EnumList;
use App\Modules\Course\Models\Course;
use App\Modules\Salary\Enums\Level;

/**
 * Класс модель для таблицы разделов на основе Eloquent.
 *
 * @property int|string $id ID раздела.
 * @property int|string $metatag_id ID метатегов.
 * @property string $name Название.
 * @property string $header Заголовок.
 * @property string $text Текст.
 * @property string $additional Дополнительное описание.
 * @property string $level Уровень.
 * @property bool $free Признак бесплатности.
 * @property string $status Статус.
 *
 * @property-read Metatag $metatag
 * @property-read SectionItem[] $items
 */
class Section extends Eloquent
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
        'name',
        'header',
        'text',
        'additional',
        'level',
        'free',
        'status',
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
            'name' => 'required|between:1,191',
            'header' => 'max:191',
            'text' => 'max:65000',
            'additional' => 'max:65000',
            'level' => 'in:' . implode(',', EnumList::getValues(Level::class)),
            'free' => 'required|boolean',
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
            'metatag_id' => trans('section::models.section.metatagId'),
            'name' => trans('section::models.section.name'),
            'header' => trans('section::models.section.header'),
            'text' => trans('section::models.section.text'),
            'additional' => trans('section::models.section.additional'),
            'level' => trans('section::models.section.level'),
            'free' => trans('section::models.section.free'),
            'status' => trans('section::models.section.status'),
        ];
    }

    /**
     * Класс фильтр.
     *
     * @return string Название класса фильтра.
     */
    public function modelFilter(): string
    {
        return $this->provideFilter(SectionFilter::class);
    }

    /**
     * Создание новой фабрики для модели.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return SectionFactory::new();
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
     * Элементы раздела.
     *
     * @return HasMany Модели курсов.
     */
    public function items(): HasMany
    {
        return $this->hasMany(Course::class);
    }
}
