<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Elastic\Query;

/**
 * Построитель запросов к Elasticsearch.
 */
class ElasticQuery
{
    /**
     * Лимит.
     *
     * @var int|null
     */
    private ?int $limit = null;

    /**
     * Отступ.
     *
     * @var int|null
     */
    private ?int $offset = null;

    /**
     * Поля на вывод.
     *
     * @var array|null
     */
    private ?array $fields = [];

    /**
     * Получить запрос для фильтрации.
     *
     * @return array Запрос для фильтрации.
     */
    public function getBody(): array
    {
        $body = [];

        if ($this->limit) {
            $body['size'] = $this->limit;
        }

        if ($this->offset) {
            $body['from'] = $this->offset;
        }

        if (count($this->fields)) {
            $body['_source'] = $this->fields;
        }

        return $body;
    }

    /**
     * Установка лимита.
     *
     * @param int $limit Лимит
     *
     * @return $this
     */
    public function setLimit(int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * Установка отступа.
     *
     * @param int|null $offset Отступ.
     *
     * @return $this
     */
    public function setOffset(?int $offset): self
    {
        if ($offset) {
            $this->offset = $offset;
        } else {
            $this->offset = null;
        }

        return $this;
    }

    /**
     * Установка поля на вывод.
     *
     * @param array $fields Массив полей.
     *
     * @return $this
     */
    public function setFields(array $fields): self
    {
        $this->fields = $fields;

        return $this;
    }
}
