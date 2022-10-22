<?php
/**
 * Контракты.
 * Этот пакет содержит контракты ядра системы.
 *
 * @package App.Models.Contracts
 */

namespace App\Models\Contracts;

use App\Models\Entity;
use Closure;

/**
 * Интерфейс для разработки собственного pipeline.
 */
interface Pipe
{
    /**
     * Метод, который будет вызван у pipeline.
     *
     * @param  Entity $entity  Сущность для хранения данных.
     * @param  Closure  $next  Ссылка на следующий pipe.
     *
     * @return mixed Вернет значение полученное после выполнения следующего pipe.
     */
    public function handle(Entity $entity, Closure $next): mixed;
}
