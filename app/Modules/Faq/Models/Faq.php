<?php
/**
 * Модуль FAQ's.
 * Этот модуль содержит все классы для работы с FAQ's.
 *
 * @package App\Modules\Faq
 */

namespace App\Modules\Faq\Models;

use App\Modules\School\Models\School;
use Eloquent;
use App\Models\Delete;
use App\Models\Validate;
use App\Models\Sortable;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Modules\Faq\Database\Factories\FaqFactory;
use App\Modules\Faq\Filters\FaqFilter;

/**
 * Класс модель для таблицы FAQ на основе Eloquent.
 *
 * @property int|string $id ID FAQ.
 * @property int|string $school_id ID школы.
 * @property string $question Вопрос.
 * @property string $answer Ответ.
 * @property string $status Статус.
 *
 * @property-read School $school
 */
class Faq extends Eloquent
{
    use Delete;
    use HasFactory;
    use Sortable;
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
        'school_id',
        'question',
        'answer',
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
            'school_id' => 'required|digits_between:0,20|exists_soft:schools,id',
            'question' => 'required|between:1,191',
            'answer' => 'required|between:1,5000',
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
            'school_id' => trans('faq::models.faq.schoolId'),
            'question' => trans('faq::models.faq.question'),
            'answer' => trans('faq::models.faq.answer'),
            'status' => trans('faq::models.faq.status')
        ];
    }

    /**
     * Класс фильтр.
     *
     * @return string Название класса фильтра.
     */
    public function modelFilter(): string
    {
        return $this->provideFilter(FaqFilter::class);
    }

    /**
     * Создание новой фабрики для модели.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return FaqFactory::new();
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
