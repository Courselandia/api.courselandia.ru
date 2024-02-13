<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Models;

use App\Models\Validate;
use Jenssegers\Mongodb\Eloquent\Model;

/**
 * Класс модель для таблицы курсов на основе MongoDB.
 *
 * @property int|string|null $id ID.
 * @property int|string|null $uuid ID фильтра.
 * @property int|string|null $link Ссылка фильтра.
 * @property int|string|null $category Категория фильтра.
 * @property string|null $data Данные.
 */
class CourseMongoDb extends Model
{
    /**
     * Соединение.
     *
     * @var string
     */
    protected $connection = 'mongodb';

    /**
     * Название коллекции.
     *
     * @var string
     */
    protected $collection = 'courses';

    use Validate;

    /**
     * Атрибуты, для которых разрешено массовое назначение.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'uuid',
        'link',
        'category',
        'data',
    ];

    /**
     * Метод, который должен вернуть все правила валидации.
     *
     * @return array Вернет массив правил.
     */
    protected function getRules(): array
    {
        return [
            'uuid' => 'digits_between:0,20',
            'category' => 'required|max:191',
            'link' => 'max:191',
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
            'uuid' => trans('course::models.courseMongoDb.uuid'),
            'link' => trans('course::models.courseMongoDb.link'),
            'category' => trans('course::models.courseMongoDb.category'),
            'data' => trans('course::models.courseMongoDb.data'),
        ];
    }
}
