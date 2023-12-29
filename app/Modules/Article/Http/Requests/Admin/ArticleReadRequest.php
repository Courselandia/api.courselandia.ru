<?php
/**
 * Статьи написанные искусственным интеллектом для разных сущностей.
 * Пакет содержит классы для хранения статей написанных искусственным интеллектом.
 *
 * @package App.Models.Article
 */

namespace App\Modules\Article\Http\Requests\Admin;

use App\Models\Enums\EnumList;
use App\Models\FormRequest;
use App\Modules\Article\Enums\Status;
use JetBrains\PhpStorm\ArrayShape;
use Schema;

/**
 * Класс запрос для чтения категорий.
 */
class ArticleReadRequest extends FormRequest
{
    /**
     * Возвращает правила проверки.
     *
     * @return array Массив правил проверки.
     */
    #[ArrayShape([
        'sorts' => 'string',
        'offset' => 'string',
        'limit' => 'string',
        'filters' => 'string',
        'filters.status.*' => 'string',
    ])] public function rules(): array
    {
        $columns = Schema::getColumnListing('articles');
        $columns = implode(',', $columns);

        return [
            'sorts' => 'array|sorts:' . $columns,
            'offset' => 'integer|digits_between:0,20',
            'limit' => 'integer|digits_between:0,20',
            'filters' => 'array|filters:' . $columns . '|filter_date_range:published_at',
            'filters.status.*' => 'in:' . implode(',', EnumList::getValues(Status::class)),
        ];
    }

    /**
     * Возвращает атрибуты.
     *
     * @return array Массив атрибутов.
     */
    #[ArrayShape([
        'sorts' => 'string',
        'offset' => 'string',
        'limit' => 'string',
        'filters' => 'string',
        'filters.status' => 'string',
    ])] public function attributes(): array
    {
        return [
            'sorts' => trans('article::http.requests.admin.articleReadRequest.sorts'),
            'offset' => trans('article::http.requests.admin.articleReadRequest.offset'),
            'limit' => trans('article::http.requests.admin.articleReadRequest.limit'),
            'filters' => trans('article::http.requests.admin.articleReadRequest.filters'),
            'filters.status.*' => trans('article::http.requests.admin.articleReadRequest.status'),
        ];
    }
}
