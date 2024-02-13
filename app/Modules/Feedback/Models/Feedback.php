<?php
/**
 * Модуль Обратной связи.
 * Этот модуль содержит все классы для работы с обратной связью.
 *
 * @package App\Modules\Feedback
 */

namespace App\Modules\Feedback\Models;

use App\Models\Sortable;
use App\Modules\Feedback\Database\Factories\FeedbackFactory;
use App\Modules\Feedback\Filters\FeedbackFilter;
use Eloquent;
use App\Models\Validate;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Delete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Класс модель для таблицы обратной связи на основе Eloquent.
 *
 * @property int|string $id
 * @property string $name Имя.
 * @property string $email E-mail.
 * @property string|null $phone Телефон.
 * @property string|null $message Сообщение.
 */
class Feedback extends Eloquent
{
    use Delete;
    use HasFactory;
    use Sortable;
    use SoftDeletes;
    use Validate;
    use Filterable;
    use HasTimestamps;

    /**
     * Название таблицы.
     *
     * @var string
     */
    protected $table = 'feedbacks';

    /**
     * Атрибуты, для которых разрешено массовое назначение.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'email',
        'phone',
        'message'
    ];

    /**
     * Метод, который должен вернуть все правила валидации.
     *
     * @return array Вернет массив правил.
     */
    protected function getRules(): array
    {
        return [
            'name' => 'required|between:1,191',
            'email' => 'required|email',
            'phone' => 'nullable|phone:7',
            'message' => 'nullable|max:5000'
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
            'name' => trans('feedback::models.feedback.name'),
            'email' => trans('feedback::models.feedback.email'),
            'phone' => trans('feedback::models.feedback.phone'),
            'message' => trans('feedback::models.feedback.message')
        ];
    }

    /**
     * Класс фильтр.
     *
     * @return string Название класса фильтра.
     */
    public function modelFilter(): string
    {
        return $this->provideFilter(FeedbackFilter::class);
    }

    /**
     * Создание новой фабрики для модели.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return FeedbackFactory::new();
    }
}
