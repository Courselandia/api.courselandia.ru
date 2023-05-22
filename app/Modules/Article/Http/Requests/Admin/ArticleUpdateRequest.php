<?php
/**
 * Статьи написанные искусственным интеллектом для разных сущностей.
 * Пакет содержит классы для хранения статей написанных искусственным интеллектом.
 *
 * @package App.Models.Article
 */

namespace App\Modules\Article\Http\Requests\Admin;

use App\Models\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Класс запрос для обновления статьи.
 */
class ArticleUpdateRequest extends FormRequest
{
    /**
     * Возвращает правила проверки.
     *
     * @return array Массив правил проверки.
     */
    #[ArrayShape([
        'apply' => 'string',
    ])] public function rules(): array
    {
        return [
            'apply' => 'boolean'
        ];
    }

    /**
     * Возвращает атрибуты.
     *
     * @return array Массив атрибутов.
     */
    #[ArrayShape([
        'apply' => 'string',
    ])] public function attributes(): array
    {
        return [
            'apply' => trans('article::http.requests.admin.articleUpdateRequest.apply'),
        ];
    }
}
