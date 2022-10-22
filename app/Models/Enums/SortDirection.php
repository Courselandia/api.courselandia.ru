<?php
/**
 * Перечисления.
 * Этот пакет содержит перечисления для ядра системы.
 *
 * @package App.Models.Enums
 */
namespace App\Models\Enums;

/**
 * Направление сортировки.
 */
enum SortDirection: string
{
    /**
     * Сортировка по возрастанию.
     */
    case ASC = 'ASC';

    /**
     * Сортировка по убыванию.
     */
    case DESC = 'DESC';
}