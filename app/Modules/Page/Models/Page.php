<?php
/**
 * Модуль Страницы.
 * Этот модуль содержит все классы для работы со списком страниц.
 *
 * @package App\Modules\Page
 */

namespace App\Modules\Page\Models;

use App\Modules\Crawl\Models\Crawl;
use Eloquent;
use Carbon\Carbon;
use App\Models\Delete;
use App\Models\Validate;
use App\Models\Sortable;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Modules\Page\Database\Factories\PageFactory;
use App\Modules\Page\Filters\PageFilter;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Класс модель для таблицы страниц на основе Eloquent.
 *
 * @property int|string $id ID страницы.
 * @property string $path Путь к странице.
 * @property Carbon $lastmod Дата обновления.
 *
 * @property-read Crawl $crawl Дата обновления.
 */
class Page extends Eloquent
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
        'lastmod' => 'datetime',
    ];


    /**
     * Атрибуты, для которых разрешено массовое назначение.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'path',
        'lastmod',
    ];

    /**
     * Метод, который должен вернуть все правила валидации.
     *
     * @return array Вернет массив правил.
     */
    protected function getRules(): array
    {
        return [
            'path' => 'required|between:1,191|unique_soft:pages,path,' . $this->id . ',id',
            'lastmod' => 'required|date',
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
            'path' => trans('page::models.page.path'),
            'lastmod' => trans('page::models.page.lastmod'),
        ];
    }

    /**
     * Класс фильтр.
     *
     * @return string Название класса фильтра.
     */
    public function modelFilter(): string
    {
        return $this->provideFilter(PageFilter::class);
    }

    /**
     * Создание новой фабрики для модели.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return PageFactory::new();
    }

    /**
     * Получить запись индексации.
     *
     * @return HasOne Модель индексации.
     */
    public function crawl(): HasOne
    {
        return $this->hasOne(Crawl::class);
    }
}
