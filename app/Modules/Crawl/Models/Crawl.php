<?php
/**
 * Модуль индексации страниц.
 * Этот модуль содержит все классы для системы индексации страниц поисковыми системами.
 *
 * @package App\Modules\Crawl
 */

namespace App\Modules\Crawl\Models;

use Carbon\Carbon;
use Eloquent;
use App\Models\Delete;
use App\Models\Validate;
use App\Models\Sortable;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Modules\Crawl\Database\Factories\CrawlFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Modules\Crawl\Filters\CrawlFilter;
use App\Modules\Page\Models\Page;

/**
 * Класс модель для таблицы индексации на основе Eloquent.
 *
 * @property int|string $id ID индексации.
 * @property int|string $page_id ID страницы.
 * @property Carbon $pushed_at Дата отправки на индексацию.
 * @property string $engine Поисковая система.
 *
 * @property-read Page $page
 */
class Crawl extends Eloquent
{
    use Delete;
    use HasFactory;
    use Sortable;
    use SoftDeletes;
    use Validate;
    use Filterable;
    use HasTimestamps;

    /**
     * Типизирование атрибутов.
     *
     * @var array
     */
    protected $casts = [
        'pushed_at' => 'datetime',
    ];

    /**
     * Атрибуты, для которых разрешено массовое назначение.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'page_id',
        'pushed_at',
        'engine',
    ];

    /**
     * Метод, который должен вернуть все правила валидации.
     *
     * @return array Вернет массив правил.
     */
    protected function getRules(): array
    {
        return [
            'page_id' => 'required|digits_between:0,20',
            'pushed_at' => 'date',
            'engine' => 'required|between:1,50',
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
            'page_id' => trans('direction::models.crawl.pageId'),
            'pushed_at' => trans('direction::models.crawl.pushedAt'),
            'engine' => trans('direction::models.crawl.engine'),
        ];
    }

    /**
     * Класс фильтр.
     *
     * @return string Название класса фильтра.
     */
    public function modelFilter(): string
    {
        return $this->provideFilter(CrawlFilter::class);
    }

    /**
     * Создание новой фабрики для модели.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return CrawlFactory::new();
    }

    /**
     * Получить страницу.
     *
     * @return BelongsTo Модель страницы.
     */
    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }
}
