<?php
/**
 * Модуль Категорий.
 * Этот модуль содержит все классы для работы с категориями.
 *
 * @package App\Modules\Category
 */

namespace App\Modules\Category\Models;

use App\Modules\Analyzer\Models\Analyzer;
use App\Modules\Article\Models\Article;
use App\Modules\Course\Models\Course;
use App\Modules\Direction\Models\Direction;
use App\Modules\Profession\Models\Profession;
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
use App\Modules\Category\Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Modules\Category\Filters\CategoryFilter;

/**
 * Класс модель для таблицы категорий на основе Eloquent.
 *
 * @property int|string $id ID категории.
 * @property int|string $metatag_id ID метатегов.
 * @property string $name Название.
 * @property string $header Заголовок.
 * @property string $header_template Шаблон заголовка.
 * @property string $link Ссылка.
 * @property string $text Текст.
 * @property string $status Статус.
 *
 * @property-read Metatag $metatag
 * @property-read Direction[] $directions
 * @property-read Profession[] $professions
 * @property-read Course[] $courses
 * @property-read Article[] $articles
 * @property-read Analyzer[] $analyzers
 */
class Category extends Eloquent
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
            'link' => 'required|between:1,191|alpha_dash|unique_soft:categories,link,' . $this->id . ',id',
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
            'metatag_id' => trans('category::models.category.metatagId'),
            'name' => trans('category::models.category.name'),
            'header' => trans('category::models.category.header'),
            'headerTemplate' => trans('category::models.category.headerTemplate'),
            'link' => trans('category::models.category.link'),
            'text' => trans('category::models.category.text'),
            'status' => trans('category::models.category.status')
        ];
    }

    /**
     * Класс фильтр.
     *
     * @return string Название класса фильтра.
     */
    public function modelFilter(): string
    {
        return $this->provideFilter(CategoryFilter::class);
    }

    /**
     * Создание новой фабрики для модели.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return CategoryFactory::new();
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
     * Направления этой категории.
     *
     * @return BelongsToMany Модели направлений.
     */
    public function directions(): BelongsToMany
    {
        return $this->belongsToMany(Direction::class);
    }

    /**
     * Профессии этой категории.
     *
     * @return BelongsToMany Модели профессий.
     */
    public function professions(): BelongsToMany
    {
        return $this->belongsToMany(Profession::class);
    }

    /**
     * Курсы этой категории.
     *
     * @return BelongsToMany Модели курсов.
     */
    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_category');
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
