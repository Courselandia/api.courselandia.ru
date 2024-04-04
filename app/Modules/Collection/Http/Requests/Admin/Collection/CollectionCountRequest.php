<?php
/**
 * Модуль Коллекций.
 * Этот модуль содержит все классы для работы с коллекциями.
 *
 * @package App\Modules\Collection
 */

namespace App\Modules\Collection\Http\Requests\Admin\Collection;

use App\Models\FormRequest;
use Illuminate\Support\Str;

/**
 * Класс запрос для получения количества курсов в коллекции.
 */
class CollectionCountRequest extends FormRequest
{
    /**
     * Конвертация отправленных данных.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'filters' => Str::isJson($this->get('filters'))
                ? json_decode($this->get('filters'), true)
                : $this->get('filters'),
        ]);
    }

    /**
     * Возвращает правила проверки.
     *
     * @return array Массив правил проверки.
     */
    public function rules(): array
    {
        return [
            'filters.*.name' => 'required|between:1,191',
            'filters.*.value' => 'required|json',
        ];
    }

    /**
     * Возвращает атрибуты.
     *
     * @return array Массив атрибутов.
     */
    public function attributes(): array
    {
        return [
            'filters.*.name' => trans('collection::http.requests.admin.collectionCountRequest.filters'),
            'filters.*.value' => trans('collection::http.requests.admin.collectionCountRequest.filters'),
        ];
    }
}
