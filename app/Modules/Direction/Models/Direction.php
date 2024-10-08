<?php
/**
 * Модуль Направления.
 * Этот модуль содержит все классы для работы с направлениями.
 *
 * @package App\Modules\Direction
 */

namespace App\Modules\Direction\Models;

use App\Modules\Analyzer\Models\Analyzer;
use App\Modules\Article\Models\Article;
use App\Modules\Category\Models\Category;
use App\Modules\Course\Models\Course;
use App\Modules\Teacher\Models\Teacher;
use Eloquent;
use App\Models\Status;
use App\Models\Delete;
use App\Models\Validate;
use App\Models\Sortable;
use EloquentFilter\Filterable;
use App\Modules\Metatag\Models\Metatag;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Modules\Direction\Database\Factories\DirectionFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Modules\Direction\Filters\DirectionFilter;

/**
 * Класс модель для таблицы направлений на основе Eloquent.
 *
 * @property int|string $id ID направления.
 * @property int|string $metatag_id ID метатегов.
 * @property string $name Название.
 * @property string $header Заголовок.
 * @property string $header_template Шаблон заголовка.
 * @property string $weight Вес.
 * @property string $link Ссылка.
 * @property string $text Текст.
 * @property string $additional Дополнительное описание.
 * @property string $status Статус.
 *
 * @property-read Metatag $metatag
 * @property-read Category[] $categories
 * @property-read Teacher[] $teachers
 * @property-read Course[] $courses
 * @property-read Article[] $articles
 * @property-read Analyzer[] $analyzers
 */
class Direction extends Eloquent
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
        'weight',
        'link',
        'text',
        'additional',
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
            'weight' => 'integer|digits_between:0,5',
            'link' => 'required|between:1,191|alpha_dash|unique_soft:directions,link,' . $this->id . ',id',
            'text' => 'max:65000',
            'additional' => 'max:65000',
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
            'metatag_id' => trans('direction::models.direction.metatagId'),
            'name' => trans('direction::models.direction.name'),
            'header' => trans('direction::models.direction.header'),
            'header_template' => trans('direction::models.direction.headerTemplate'),
            'weight' => trans('direction::models.direction.weight'),
            'link' => trans('direction::models.direction.link'),
            'text' => trans('direction::models.direction.text'),
            'additional' => trans('direction::models.direction.additional'),
            'status' => trans('direction::models.direction.status')
        ];
    }

    /**
     * Класс фильтр.
     *
     * @return string Название класса фильтра.
     */
    public function modelFilter(): string
    {
        return $this->provideFilter(DirectionFilter::class);
    }

    /**
     * Создание новой фабрики для модели.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return DirectionFactory::new();
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
     * Категории этого направления.
     *
     * @return BelongsToMany Модели категорий.
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    /**
     * Учителя этого направления.
     *
     * @return BelongsToMany Модели учителей.
     */
    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(Teacher::class);
    }

    /**
     * Курсы этого направления.
     *
     * @return BelongsToMany Модели курсов.
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
