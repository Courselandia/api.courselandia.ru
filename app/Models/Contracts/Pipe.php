<?php
/**
 * Контракты.
 * Этот пакет содержит контракты ядра системы.
 *
 * @package App.Models.Contracts
 */

namespace App\Models\Contracts;

use App\Models\DTO;
use Closure;

/**
 * Интерфейс для разработки собственного pipeline.
 */
interface Pipe
{
    /**
     * Метод, который будет вызван у pipeline.
     *
     * @param DTO $data Data Transfer Object..
     * @param Closure $next Ссылка на следующий pipe.
     *
     * @return mixed Вернет значение полученное после выполнения следующего pipe.
     */
    public function handle(DTO $data, Closure $next): mixed;
}
