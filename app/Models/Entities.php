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
     * @var string
     */
    private string|array $entity;

    /**
     * Конструктор.
     *
     * @param string|array $entity Название класса сущности.
     * @param string|null $nameClass Название класса, который используется в $entity на тот случай если их там несколько. Используется при полиморфной связи.
     */
    public function __construct(string|array $entity, ?string $nameClass)
    {
        $this->entity = $entity;
    }

    /**
     * Вернет объект сущности.
     *
     * @return Entity
     */
    public function getEntity(): Entity
    {
        return new $this->entity();
    }
}
