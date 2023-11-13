<?php
/**
 * Статьи написанные искусственным интеллектом для разных сущностей.
 * Пакет содержит классы для хранения статей написанных искусственным интеллектом.
 *
 * @package App.Models.Analyzer
 */

namespace App\Modules\Analyzer\Http\Requests\Admin;

use App\Models\Enums\EnumList;
use App\Models\FormRequest;
use App\Modules\Analyzer\Enums\Status;
use JetBrains\PhpStorm\ArrayShape;
use Schema;

/**
 * Класс запрос для чтения.
 */
class AnalyzerReadRequest extends FormRequest
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
        'filters.status' => 'string',
    ])] public function rules(): array
    {
        $columns = Schema::getColumnListing('analyzers');
        $columns = [
            ...$columns,
            'analyzerable-status',
        ];
        $columns = implode(',', $columns);

        return [
            'sorts' => 'array|sorts:' . $columns,
            'offset' => 'integer|digits_between:0,20',
            'limit' => 'integer|digits_between:0,20',
            'filters' => 'array|filters:' . $columns . '|filter_date_range:published_at',
            'filters.status' => 'in:' . implode(',', EnumList::getValues(Status::class)),
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
            'sorts' => trans('analyzer::http.requests.admin.analyzerReadRequest.sorts'),
            'offset' => trans('analyzer::http.requests.admin.analyzerReadRequest.offset'),
            'limit' => trans('analyzer::http.requests.admin.analyzerReadRequest.limit'),
            'filters' => trans('analyzer::http.requests.admin.analyzerReadRequest.filters'),
            'filters.status' => trans('analyzer::http.requests.admin.analyzerReadRequest.status'),
        ];
    }
}
