<?php
/**
 * Модуль Промоакций.
 * Этот модуль содержит все классы для работы с промоакциями.
 *
 * @package App\Modules\Promotion
 */

namespace App\Modules\Promotion\Models;

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
use App\Modules\Promotion\Database\Factories\PromotionFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Modules\Promotion\Filters\PromotionFilter;
use App\Modules\School\Models\School;

/**
 * Класс модель для таблицы промоакций на основе Eloquent.
 *
 * @property int|string $id ID промоакции.
 * @property int|string $school_id ID школы.
 * @property null|string $uuid ID источника промоакции.
 * @property string $title Название.
 * @property null|string $description Описание.
 * @property null|Carbon $date_start Дата начала.
 * @property null|Carbon $date_end Дата окончания.
 * @property string $status Статус.
 *
 * @property-read School $school
 */
class Promotion extends Eloquent
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
        'title',
        'description',
        'date_start',
        'date_end',
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
            'title' => 'required|between:1,191',
            'description' => 'max:65000',
            'date_start' => 'date',
            'date_end' => 'date',
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
            'school_id' => trans('promotion::models.promotion.schoolId'),
            'uuid' => trans('promotion::models.promotion.uuid'),
            'title' => trans('promotion::models.promotion.title'),
            'description' => trans('promotion::models.promotion.description'),
            'date_start' => trans('promotion::models.promotion.dateStart'),
            'date_end' => trans('promotion::models.promotion.dateEnd'),
            'status' => trans('promotion::models.promotion.status')
        ];
    }

    /**
     * Класс фильтр.
     *
     * @return string Название класса фильтра.
     */
    public function modelFilter(): string
    {
        return $this->provideFilter(PromotionFilter::class);
    }

    /**
     * Создание новой фабрики для модели.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return PromotionFactory::new();
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
