<?php
/**
 * Статьи написанные искусственным интеллектом для разных сущностей.
 * Пакет содержит классы для хранения статей написанных искусственным интеллектом.
 *
 * @package App.Models.Article
 */

namespace App\Modules\Article\Contracts;

/**
 * Абстрактный класс для создания собственного драйвера написания и принятия текста.
 */
abstract class ArticleCategory
{
    /**
     * Абстрактный метод для получения названия категории.
     *
     * @return string Название категории.
     */
    abstract public function name(): string;

    /**
     * Название колонки, которая хранит текст, что должен быть изменен.
     *
     * @return string Название колонки.
     */
    abstract public function field(): string;

    /**
     * Абстрактный метод для создания собственной логики принятия текста.
     *
     * @param int $id ID статьи.
     *
     * @return void
     */
    abstract public function apply(int $id): void;

    /**
     * Шаблон запроса к искусственному интеллекту.
     *
     * @param int $id ID сущности для которой пишется статья.
     *
     * @return string Запрос.
     */
    abstract public function requestTemplate(int $id): string;

    /**
     * Абстрактный метод для получения метки, которая характеризует сущность.
     *
     * @param int $id ID сущности для которой пишется статья.
     *
     * @return string Метка.
     */
    abstract public function label(int $id): string;
}
