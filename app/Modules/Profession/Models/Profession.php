<?php
/**
 * Модуль Профессии.
 * Этот модуль содержит все классы для работы с профессиями.
 *
 * @package App\Modules\Profession
 */

namespace App\Modules\Profession\Models;

use App\Modules\Analyzer\Models\Analyzer;
use App\Modules\Article\Models\Article;
use App\Modules\Course\Models\Course;
use App\Modules\Salary\Models\Salary;
use Eloquent;
use App\Models\Status;
use App\Models\Delete;
use App\Models\Validate;
use App\Models\Sortable;
use EloquentFilter\Filterable;
use App\Modules\Metatag\Models\Metatag;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Modules\Profession\Database\Factories\ProfessionFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Modules\Profession\Filters\ProfessionFilter;

/**
 * Класс модель для таблицы профессий на основе Eloquent.
 *
 * @property int|string $id ID профессии.
 * @property int|string $metatag_id ID метатегов.
 * @property string $name Название.
 * @property string $header Заголовок.
 * @property string $header_template Шаблон заголовка.
 * @property string $link Ссылка.
 * @property string $text Текст.
 * @property string $status Статус.
 *
 * @property-read Metatag $metatag
 * @property-read Salary[] $salaries
 * @property-read Course[] $courses
 * @property-read Article[] $articles
 * @property-read Analyzer[] $analyzers
 */
class Profession extends Eloquent
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
        'header_template',
        'link',
        'text',
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
            'header_template' => 'max:191',
            'link' => 'required|between:1,191|alpha_dash|unique_soft:professions,link,' . $this->id . ',id',
            'text' => 'max:65000',
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
            'metatag_id' => trans('profession::models.profession.metatagId'),
            'name' => trans('profession::models.profession.name'),
            'header' => trans('profession::models.profession.header'),
            'header_template' => trans('profession::models.profession.headerTemplate'),
            'link' => trans('profession::models.profession.link'),
            'text' => trans('profession::models.profession.text'),
            'status' => trans('profession::models.profession.status')
        ];
    }

    /**
     * Класс фильтр.
     *
     * @return string Название класса фильтра.
     */
    public function modelFilter(): string
    {
        return $this->provideFilter(ProfessionFilter::class);
    }

    /**
     * Создание новой фабрики для модели.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return ProfessionFactory::new();
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
     * Зарплаты этой профессии.
     *
     * @return HasMany Модели зарплат.
     */
    public function salaries(): HasMany
    {
        return $this->hasMany(Salary::class);
    }

    /**
     * Курсы этой профессии.
     *
     * @return BelongsToMany Модели профессий.
     */
    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class);
    }

    /**
     * Статьи написанные искусственным интеллектом.
     *
     * @return MorphMany Модели статей.
     */
    public function articles(): MorphMany
    {
        return $this->morphMany(Article::class, 'articleable');
    }

    /**
     * Результаты анализа текста.
     *
     * @return MorphMany Модели анализа текста.
     */
    public function analyzers(): MorphMany
    {
        return $this->morphMany(Analyzer::class, 'analyzerable');
    }
}
