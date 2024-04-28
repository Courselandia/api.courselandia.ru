<?php
/**
 * Модуль Менеджер Заданий.
 * Этот модуль содержит все классы для работы с заданиями.
 *
 * @package App\Modules\Task
 */

namespace App\Modules\Task\Models;

use Carbon\Carbon;
use Eloquent;
use App\Models\Status;
use App\Models\Delete;
use App\Models\Validate;
use App\Models\Sortable;
use App\Models\Enums\EnumList;
use App\Modules\User\Models\User;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Modules\Task\Database\Factories\TaskFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Modules\Task\Filters\TaskFilter;
use App\Modules\Task\Enums\Status as TaskStatus;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Builder;

/**
 * Класс модель для таблицы заданий на основе Eloquent.
 *
 * @property int|string $id ID навыка.
 * @property int|string $user_id ID пользователя.
 * @property string $name Название.
 * @property string|null $reason Причина ошибки.
 * @property string $status Статус.
 * @property Carbon|null $launched_at Дата запуска.
 * @property Carbon|null $finished_at Дата остановки.
 *
 * @property-read User $user
 */
class Task extends Eloquent
{
    use Delete;
    use HasFactory;
    use Sortable;
    use SoftDeletes;
    use Status;
    use Validate;
    use Filterable;
    use HasTimestamps;
    use Prunable;

    /**
     * Типизирование атрибутов.
     *
     * @var array
     */
    protected $casts = [
        'launched_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    /**
     * Атрибуты, для которых разрешено массовое назначение.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'user_id',
        'name',
        'reason',
        'status',
        'launched_at',
        'finished_at',
    ];

    /**
     * Метод, который должен вернуть все правила валидации.
     *
     * @return array Вернет массив правил.
     */
    protected function getRules(): array
    {
        return [
            'user_id' => 'digits_between:0,20',
            'name' => 'required|between:1,191',
            'reason' => 'max:5000',
            'status' => 'required|in:' . implode(',', EnumList::getValues(TaskStatus::class)),
            'launched_at' => 'nullable|date',
            'finished_at' => 'nullable|date',
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
            'user_id' => trans('task::models.task.userId'),
            'name' => trans('task::models.task.name'),
            'reason' => trans('task::models.task.reason'),
            'status' => trans('task::models.task.status'),
            'launched_at' => trans('task::models.task.launchedAt'),
            'finished_at' => trans('task::models.task.finishedAt'),
        ];
    }

    /**
     * Класс фильтр.
     *
     * @return string Название класса фильтра.
     */
    public function modelFilter(): string
    {
        return $this->provideFilter(TaskFilter::class);
    }

    /**
     * Создание новой фабрики для модели.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return TaskFactory::new();
    }

    /**
     * Получить пользователя.
     *
     * @return BelongsTo Модель пользователя.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Очистка старых данных.
     *
     * @return Builder Построитель запросов.
     */
    public function prunable(): Builder
    {
        return static::where('created_at', '<=', now()->subMonths(2));
    }
}
