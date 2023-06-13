<?php
/**
 * Анализатор текстов для SEO проверки.
 * Пакет содержит классы для хранения результатов анализа текстов для SEO.
 *
 * @package App.Models.Analyzer
 */

namespace App\Modules\Analyzer\Contracts;

/**
 * Абстрактный класс для создания собственного драйвера анализирования текста.
 */
abstract class AnalyzerCategory
{
    /**
     * Абстрактный метод для получения названия категории.
     *
     * @return string Название категории.
     */
    abstract public function name(): string;

    /**
     * Название колонки, которая хранит текст, что должен быть проанализирован.
     *
     * @return string Название колонки.
     */
    abstract public function field(): string;

    /**
     * Абстрактный метод для создания собственной логики получения текста для анализирования.
     *
     * @param int $id ID сущности для которой осуществляется анализирование текста.
     *
     * @return string Текст для проверки.
     */
    abstract public function text(int $id): string;

    /**
     * Абстрактный метод для получения метки, которая характеризует сущность.
     *
     * @param int $id ID сущности для которой осуществляется анализирование текста.
     *
     * @return string Метка.
     */
    abstract public function label(int $id): string;
}
