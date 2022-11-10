<?php
/**
 * Модуль Зарплаты.
 * Этот модуль содержит все классы для работы с зарплатами.
 *
 * @package App\Modules\Salary
 */

namespace App\Modules\Salary\Models;

use App\Models\Enums\EnumList;
use App\Modules\Profession\Models\Profession;
use App\Modules\Salary\Enums\Level;
use Eloquent;
use App\Models\Status;
use App\Models\Delete;
use App\Models\Validate;
use App\Models\Sortable;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use JetBrains\PhpStorm\ArrayShape;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Modules\Salary\Database\Factories\SalaryFactory;
use App\Modules\Salary\Filters\SalaryFilter;

/**
 * Класс модель для таблицы зарплат на основе Eloquent.
 *
 * @property int|string $id ID зарплаты.
 * @property int|string $profession_id ID профессии.
 * @property string $level Уровень.
 * @property int $salary Зарплата.
 * @property string $status Статус.
 *
 * @property-read Profession $profession
 */
class Salary extends Eloquent
{
    use Delete;
    use HasFactory;
    use Sortable;
    use SoftDeletes;
    use Status;
    use Validate;
    use Filterable;

    /**
     * Атрибуты, для которых разрешено массовое назначение.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'profession_id',
        'level',
        'salary',
        'status',
    ];

    /**
     * Метод, который должен вернуть все правила валидации.
     *
     * @return array Вернет массив правил.
     */
    #[ArrayShape([
        'profession_id' => 'string',
        'level' => 'string',
        'salary' => 'string',
        'status' => 'string'
    ])] protected function getRules(): array
    {
        return [
            'profession_id' => 'required|digits_between:0,20|exists_soft:professions,id',
            'level' => 'required|in:' . implode(',', EnumList::getValues(Level::class)),
            'salary' => 'required|integer',
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
            'profession_id' => trans('salary::models.salary.professionId'),
            'level' => trans('salary::models.salary.level'),
            'salary' => trans('salary::models.salary.salary'),
            'status' => trans('salary::models.salary.status')
        ];
    }

    /**
     * Класс фильтр.
     *
     * @return string Название класса фильтра.
     */
    public function modelFilter(): string
    {
        return $this->provideFilter(SalaryFilter::class);
    }

    /**
     * Создание новой фабрики для модели.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return SalaryFactory::new();
    }

    /**
     * Получить профессию.
     *
     * @return BelongsTo Модель профессии.
     */
    public function profession(): BelongsTo
    {
        return $this->belongsTo(Profession::class);
    }
}
