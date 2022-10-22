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
    private string $entity;

    /**
     * Конструктор.
     *
     * @param  string  $entity  Название класса сущности.
     */
    public function __construct(string $entity)
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