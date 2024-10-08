<?php
/**
 * Ядро базовых классов.
 * Этот пакет содержит ядро базовых классов для работы с основными компонентами и возможностями системы.
 *
 * @package App.Models
 */

namespace App\Models;

use Spatie\LaravelData\Data as DataNative;

/**
 * Data Transfer Object - объект передачи данных.
 */
abstract class Data extends DataNative
{

}
