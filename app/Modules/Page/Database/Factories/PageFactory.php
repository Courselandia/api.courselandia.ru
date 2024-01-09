<?php
/**
 * Модуль Страницы.
 * Этот модуль содержит все классы для работы со списком страниц.
 *
 * @package App\Modules\Page
 */

namespace App\Modules\Page\Database\Factories;

use Carbon\Carbon;
use App\Modules\Page\Models\Page;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Фабрика модели страниц.
 */
class PageFactory extends Factory
{
    /**
     * Модель фабрики.
     *
     * @var string
     */
    protected $model = Page::class;

    /**
     * Определение модели.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'path' => $this->faker->filePath(),
            'lastmod' => Carbon::now(),
        ];
    }
}
