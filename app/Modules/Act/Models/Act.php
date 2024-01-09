<?php
/**
 * Модуль Запоминания действий.
 * Этот модуль содержит все классы для работы с запоминанием и контролем действий пользователя.
 *
 * @package App\Modules\Act
 */

namespace App\Modules\Act\Models;

use App\Models\Sortable;
use App\Modules\Act\Filters\ActFilter;
use Eloquent;
use App\Models\Validate;
use App\Models\Delete;
use EloquentFilter\Filterable;

/**
 * Класс модель для действий на основе Eloquent.
 *
 * @property int|string $id ID.
 * @property string $index Индекс.
 * @property int $count Количество.
 * @property int $minutes Минуты хранения.
 */
class Act extends Eloquent
{
    use Delete;
    use Sortable;
    use Validate;
    use Filterable;

    /**
     * Атрибуты, для которых разрешено массовое назначение.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'index',
        'count',
        'minutes'
    ];

    /**
     * Метод, который должен вернуть все правила валидации.
     *
     * @return array Вернет массив правил.
     */
    protected function getRules(
    ): array
    {
        return [
            'index' => 'required|between:1,191|unique:acts,index,'.$this->id.',id',
            'count' => 'required|integer',
            'minutes' => 'required|integer'
        ];
    }

    /**
     * Метод, который должен вернуть все названия атрибутов.
     *
     * @return array Массив возможных ошибок валидации.
     */
    protected function getNames(
    ): array
    {
        return [
            'index' => trans('act::models.act.index'),
            'count' => trans('act::models.act.count'),
            'minutes' => trans('act::models.act.Minutes')
        ];
    }

    /**
     * Класс фильтр.
     *
     * @return string Название класса фильтра.
     */
    public function modelFilter(): string
    {
        return $this->provideFilter(ActFilter::class);
    }
}
