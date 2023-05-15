<?php
/**
 * Ядро базовых классов.
 * Этот пакет содержит ядро базовых классов для работы с основными компонентами и возможностями системы.
 *
 * @package App.Models
 */

namespace App\Models;

use Attribute;

/**
 * Аннотация сущностей.
 */
#[Attribute]
class Entities
{
    /**
     * Сущность.
     *
     * @var string|array
     */
    private string|array $entity;

    /**
     * Название поле, которое хранит имя класса, что должен использоваться в качестве entity.
     *
     * @var string|null
     */
    private ?string $fieldNameClass;

    /**
     * Конструктор.
     *
     * @param string|array $entity Название класса сущности.
     * @param string|null $fieldNameClass Название поле, которое хранит имя класса, что используется в $entity на тот случай если их там несколько. Используется при полиморфной связи.
     */
    public function __construct(string|array $entity, ?string $fieldNameClass = null)
    {
        $this->entity = $entity;
        $this->fieldNameClass = $fieldNameClass;
    }

    /**
     * Вернет объект сущности.
     *
     * @return Entity
     */
    public function getEntity(): Entity
    {
        return new $this->entity;
    }

    /**
     * Вернет название поля, которое хранит название Entity, что должно использоваться при полиморфной связи.
     *
     * @return Entity
     */
    public function getFieldNameClass(): Entity
    {
        return new $this->fieldNameClass;
    }
}
