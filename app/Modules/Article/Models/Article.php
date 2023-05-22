<?php
/**
 * Статьи написанные искусственным интеллектом для разных сущностей.
 * Пакет содержит классы для хранения статей написанных искусственным интеллектом.
 *
 * @package App.Models.Article
 */

namespace App\Modules\Article\Models;

use Eloquent;
use App\Models\Delete;
use App\Models\Validate;
use App\Models\Sortable;
use EloquentFilter\Filterable;
use JetBrains\PhpStorm\ArrayShape;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Modules\Article\Database\Factories\ArticleFactory;
use App\Modules\Article\Filters\ArticleFilter;

/**
 * Класс модель для таблицы хранения написанных текстов искусственным интеллектом на основе Eloquent.
 *
 * @property int|string $id ID текста.
 * @property int|string $task_id ID задачи на написания текста.
 * @property string $category Категория.
 * @property string|null $request Запрос на написание текста.
 * @property string|null $text Текст.
 * @property array|null $params Дополнительные параметры.
 * @property int $tries Количество попыток получить результат написанного текста.
 * @property string $status Статус
 * @property int|string $articleable_id ID сущности для которой написан текст.
 * @property int|string $articleable_type Имя класса сущности для которой написан текст.
 *
 * @property-read Eloquent $articleable
 */
class Article extends Eloquent
{
    use Delete;
    use HasFactory;
    use Sortable;
    use SoftDeletes;
    use Validate;
    use Filterable;

    /**
     * Типизирование атрибутов.
     *
     * @var array
     */
    protected $casts = [
        'params' => 'array',
    ];

    /**
     * Атрибуты, для которых разрешено массовое назначение.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'task_id',
        'category',
        'request',
        'text',
        'params',
        'tries',
        'status',
        'articleable_id',
        'articleable_type',
    ];

    /**
     * Метод, который должен вернуть все правила валидации.
     *
     * @return array Вернет массив правил.
     */
    #[ArrayShape([
        'id' => 'string',
        'task_id' => 'string',
        'category' => 'string',
        'request' => 'string',
        'text' => 'string',
        'params' => 'string',
        'tries' => 'string',
        'status' => 'string',
        'articleable_id' => 'string',
        'articleable_type' => 'string',
    ])] protected function getRules(): array
    {
        return [
            'task_id' => 'nullable|digits_between:0,20',
            'category' => 'required|between:1,191',
            'request' => 'nullable|max:65000',
            'text' => 'nullable|max:65000',
            'params' => 'nullable|json',
            'tries' => 'digits_between:0,2',
            'status' => 'required|between:1,50',
            'articleable_id' => 'nullable|digits_between:0,20',
            'articleable_type' => 'nullable|between:1,191',
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
            'task_id' => trans('article::models.article.taskId'),
            'category' => trans('article::models.article.category'),
            'request' => trans('article::models.article.request'),
            'text' => trans('article::models.article.text'),
            'params' => trans('article::models.article.params'),
            'tries' => trans('article::models.article.tries'),
            'status' => trans('article::models.article.status'),
            'articleable_id' => trans('article::models.article.articleableId'),
            'articleable_type' => trans('article::models.article.articleableType'),
        ];
    }

    /**
     * Класс фильтр.
     *
     * @return string Название класса фильтра.
     */
    public function modelFilter(): string
    {
        return $this->provideFilter(ArticleFilter::class);
    }

    /**
     * Создание новой фабрики для модели.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return ArticleFactory::new();
    }

    /**
     * Получить все модели, обладающие commentable.
     */
    public function articleable(): MorphTo
    {
        return $this->morphTo();
    }
}
