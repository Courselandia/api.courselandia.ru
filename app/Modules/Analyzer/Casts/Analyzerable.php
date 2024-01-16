<?php
/**
 * Анализатор текстов для SEO проверки.
 * Пакет содержит классы для хранения результатов анализа текстов для SEO.
 *
 * @package App.Models.Analyzer
 */

namespace App\Modules\Analyzer\Casts;

use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\DataProperty;

/**
 * Типизатор для анализируемой сущности.
 */
class Analyzerable implements Cast
{
    public function cast(DataProperty $property, mixed $value, array $context): mixed
    {
        print_r($property);
        print_r($value);
        print_r($context);
        exit;
    }
}
