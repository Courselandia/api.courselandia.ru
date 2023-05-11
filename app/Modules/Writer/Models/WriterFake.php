<?php
/**
 * Искусственный интеллект писатель.
 * Пакет содержит классы для написания текстов с использованием искусственного интеллекта.
 *
 * @package App.Models.Writer
 */

namespace App\Modules\Writer\Models;

use Faker\Factory as Faker;
use App\Modules\Writer\Contracts\Writer;

/**
 * Классы драйвер для написания текстов - фейковый драйвер, созданный для тестирования.
 * Внимание, данный драйвер имитирует генерацию текстов, используйте его только для тестирования системы.
 */
class WriterFake extends Writer
{
    /**
     * Запрос на написание текста.
     *
     * @param string $request Запрос на написания текста.
     *
     * @return string ID задачи на генерацию.
     */
    public function write(string $request): string
    {
        return '10';
    }

    /**
     * Получить результат.
     *
     * @param string $id ID задачи.
     *
     * @return string Готовый текст.
     */
    public function result(string $id): string
    {
        $faker = Faker::create();

        return $faker->text(500);
    }
}
