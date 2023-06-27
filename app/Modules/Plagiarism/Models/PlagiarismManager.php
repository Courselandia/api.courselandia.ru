<?php
/**
 * Система проверки плагиата.
 * Пакет содержит классы для проведения анализа на наличие плагиата.
 *
 * @package App.Models.Plagiarism
 */

namespace App\Modules\Plagiarism\Models;

use Config;
use Illuminate\Support\Manager;

/**
 * Класс системы написание текстов.
 */
class PlagiarismManager extends Manager
{
    public function getDefaultDriver(): string
    {
        return Config::get('plagiarism.driver');
    }
}
