<?php
/**
 * Модуль Промокодов.
 * Этот модуль содержит все классы для работы с промокодами.
 *
 * @package App\Modules\Promocode
 */

namespace App\Modules\Promocode\Models;

use Carbon\Carbon;
use Eloquent;
use App\Models\Status;
use App\Models\Delete;
use App\Models\Validate;
use App\Models\Sortable;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Modules\Promocode\Database\Factories\PromocodeFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Modules\Promocode\Filters\PromocodeFilter;
use App\Modules\School\Models\School;
use App\Models\Enums\EnumList;
use App\Modules\Promocode\Enums\DiscountType;
use App\Modules\Promocode\Enums\Type;

/**
 * Класс модель для таблицы промокодов на основе Eloquent.
 *
 * @property int|string $id ID промокода.
 * @property int|string $school_id ID школы.
 * @property null|string $uuid ID источника промокода.
 * @property string $code Промокод.
 * @property string $title Название.
 * @property null|string $description Описание.
 * @property null|float $min_price Минимальная цена.
 * @property null|float $discount Скидка.
 * @property null|string $discount_type Тип скидки.
 * @property null|Carbon $date_start Дата начала.
 * @property null|Carbon $date_end Дата окончания.
 * @property string $type Тип промокода.
 * @property string $url Ссылка на сайт
 * @property string $status Статус.
 *
 * @property-read School $school
 */
class Promocode extends Eloquent
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
        'school_id',
        'uuid',
        'code',
        'title',
        'description',
        'min_price',
        'discount',
        'discount_type',
        'date_start',
        'date_end',
        'type',
        'url',
        'status',
    ];

    /**
     * Типизирование атрибутов.
     *
     * @var array
     */
    protected $casts = [
        'date_start' => 'date',
        'date_end' => 'date',
    ];

    /**
     * Метод, который должен вернуть все правила валидации.
     *
     * @return array Вернет массив правил.
     */
    protected function getRules(): array
    {
        return [
            'school_id' => 'required|digits_between:0,20',
            'uuid' => 'max:191',
            'code' => 'required|max:191',
            'title' => 'required|between:1,191',
            'description' => 'max:65000',
            'min_price' => 'nullable|float|float_between:0,9999999',
            'discount' => 'required|float|float_between:0,9999999',
            'discount_type' => 'required|in:' . implode(',', EnumList::getValues(DiscountType::class)),
            'date_start' => 'date',
            'date_end' => 'date',
            'type' => 'required|in:' . implode(',', EnumList::getValues(Type::class)),
            'url' => 'required|url|max:191',
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
            'school_id' => trans('promocode::models.promocode.schoolId'),
            'uuid' => trans('promocode::models.promocode.uuid'),
            'code' => trans('promocode::models.promocode.code'),
            'title' => trans('promocode::models.promocode.title'),
            'description' => trans('promocode::models.promocode.description'),
            'min_price' => trans('promocode::models.promocode.minPrice'),
            'discount' => trans('promocode::models.promocode.discount'),
            'discount_type' => trans('promocode::models.promocode.discountType'),
            'date_start' => trans('promocode::models.promocode.dateStart'),
            'date_end' => trans('promocode::models.promocode.dateEnd'),
            'type' => trans('promocode::models.promocode.type'),
            'url' => trans('promocode::models.promocode.url'),
            'status' => trans('promocode::models.promocode.status'),
        ];
    }

    /**
     * Класс фильтр.
     *
     * @return string Название класса фильтра.
     */
    public function modelFilter(): string
    {
        return $this->provideFilter(PromocodeFilter::class);
    }

    /**
     * Создание новой фабрики для модели.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return PromocodeFactory::new();
    }

    /**
     * Получить школу.
     *
     * @return BelongsTo Модель школы.
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
}
