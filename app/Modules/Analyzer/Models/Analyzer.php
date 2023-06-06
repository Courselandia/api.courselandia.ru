<?php
/**
 * Анализатор текстов для SEO проверки.
 * Пакет содержит классы для хранения результатов анализа текстов для SEO.
 *
 * @package App.Models.Analyzer
 */

namespace App\Modules\Analyzer\Models;

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
use App\Modules\Analyzer\Database\Factories\AnalyzerFactory;
use App\Modules\Analyzer\Filters\AnalyzerFilter;

/**
 * Класс модель для таблицы хранения анализируемых текстов для SEO на основе Eloquent.
 *
 * @property int|string $id ID текста.
 * @property int|string $task_id ID задачи на проверку текста.
 * @property string $category Категория.
 * @property float|null $unique Уникальность текста.
 * @property int|null $water Процент воды.
 * @property int|null $spam Процент спама.
 * @property array|null $params Дополнительные параметры.
 * @property int $tries Количество попыток получить результат проверки.
 * @property string $status Статус
 * @property int|string $analyzerable_id ID сущности для которой проанализирован текст.
 * @property int|string $analyzerable_type Имя класса сущности для которой проанализирован текст.
 *
 * @property-read Eloquent $analyzerable
 */
class Analyzer extends Eloquent
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
        'unique',
        'water',
        'spam',
        'params',
        'tries',
        'status',
        'analyzerable_id',
        'analyzerable_type',
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
        'unique' => 'string',
        'water' => 'string',
        'spam' => 'string',
        'params' => 'string',
        'tries' => 'string',
        'status' => 'string',
        'analyzerable_id' => 'string',
        'analyzerable_type' => 'string',
    ])] protected function getRules(): array
    {
        return [
            'task_id' => 'nullable|digits_between:0,20',
            'category' => 'required|between:1,191',
            'unique' => 'nullable|float',
            'water' => 'nullable|digits_between:0,2',
            'spam' => 'nullable|digits_between:0,2',
            'params' => 'nullable|json',
            'tries' => 'digits_between:0,2',
            'status' => 'required|between:1,50',
            'analyzerable_id' => 'nullable|digits_between:0,20',
            'analyzerable_type' => 'nullable|between:1,191',
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
            'task_id' => trans('analyzer::models.analyzer.taskId'),
            'category' => trans('analyzer::models.analyzer.category'),
            'unique' => trans('analyzer::models.analyzer.unique'),
            'water' => trans('analyzer::models.analyzer.water'),
            'spam' => trans('analyzer::models.analyzer.spam'),
            'params' => trans('analyzer::models.analyzer.params'),
            'tries' => trans('analyzer::models.analyzer.tries'),
            'status' => trans('analyzer::models.analyzer.status'),
            'analyzerable_id' => trans('analyzer::models.analyzer.analyzerableId'),
            'analyzerable_type' => trans('analyzer::models.analyzer.analyzerableType'),
        ];
    }

    /**
     * Класс фильтр.
     *
     * @return string Название класса фильтра.
     */
    public function modelFilter(): string
    {
        return $this->provideFilter(AnalyzerFilter::class);
    }

    /**
     * Создание новой фабрики для модели.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return AnalyzerFactory::new();
    }

    /**
     * Получить все модели, обладающие analyzerable.
     */
    public function analyzerable(): MorphTo
    {
        return $this->morphTo();
    }
}
