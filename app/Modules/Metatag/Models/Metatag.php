<?php
/**
 * Модуль Метатэги.
 * Этот модуль содержит все классы для работы с метатегами.
 *
 * @package App\Modules\Metatag
 */

namespace App\Modules\Metatag\Models;

use App\Modules\Metatag\Filters\MetatagFilter;
use Eloquent;
use App\Models\Status;
use App\Models\Delete;
use App\Models\Validate;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\Factory;
use JetBrains\PhpStorm\ArrayShape;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\Metatag\Database\Factories\MetatagFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Класс модель для таблицы метатэгов на основе Eloquent.
 *
 * @property int $id ID метатэгов.
 * @property string $description Описание.
 * @property string $keywords Ключевые слова.
 * @property string $title Заголовок.
 * @property string $title_template Шаблон заголовка.
 * @property string $description_template Шаблон описания.
 */
class Metatag extends Eloquent
{
    use Delete;
    use SoftDeletes;
    use Status;
    use Validate;
    use HasFactory;
    use Filterable;

    /**
     * Атрибуты, для которых разрешено массовое назначение.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'description',
        'keywords',
        'title',
        'title_template',
        'description_template',
    ];

    /**
     * Создание новой фабрики для модели.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return MetatagFactory::new();
    }

    /**
     * Метод, который должен вернуть все правила валидации.
     *
     * @return array Вернет массив правил.
     */
    #[ArrayShape([
        'description' => 'string',
        'keywords' => 'string',
        'title' => 'string',
        'title_template' => 'string',
        'description_template' => 'string',
    ])] protected function getRules(): array
    {
        return [
            'description' => 'max:1000',
            'keywords' => 'max:1000',
            'title' => 'max:500',
            'title_template' => 'max:500',
            'description_template' => 'max:500',
        ];
    }

    /**
     * Метод, который должен вернуть все названия атрибутов.
     *
     * @return array Массив возможных ошибок валидации.
     */
    #[ArrayShape([
        'description' => 'mixed',
        'keywords' => 'mixed',
        'title' => 'mixed',
        'description_template' => 'mixed',
        'title_template' => 'mixed',
    ])] protected function getNames(): array
    {
        return [
            'description' => trans('metatag::models.metatag.description'),
            'keywords' => trans('metatag::models.metatag.keywords'),
            'title' => trans('metatag::models.metatag.title'),
            'description_template' => trans('metatag::models.metatag.templateDescription'),
            'title_template' => trans('metatag::models.metatag.templateTitle'),
        ];
    }

    /**
     * Класс фильтр.
     *
     * @return string Название класса фильтра.
     */
    public function modelFilter(): string
    {
        return $this->provideFilter(MetatagFilter::class);
    }
}
