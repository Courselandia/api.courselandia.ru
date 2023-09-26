<?php
/**
 * Ядро базовых классов.
 * Этот пакет содержит ядро базовых классов для работы с основными компонентами и возможностями системы.
 *
 * @package App.Models
 */

namespace App\Models;

use App\Models\Exceptions\ParameterInvalidException;
use Carbon\Carbon;
use ReflectionClass;
use ReflectionProperty;
use ReflectionUnionType;
use stringEncode\Exception;

/**
 * Сущность.
 */
abstract class Entity
{
    /**
     * Конструктор.
     *
     * @param array|null $values Массив значений сущности.
     *
     * @throws ParameterInvalidException
     */
    public function __construct(array $values = null)
    {
        if ($values) {
            $this->set($values);
        }
    }

    /**
     * Перевод сущности в массив.
     *
     * @return array Вернет массив свойств и значений.
     * @throws ParameterInvalidException
     */
    public function toArray(): array
    {
        $values = [];

        $reflection = new ReflectionClass($this);
        $properties = $reflection->getProperties(ReflectionProperty::IS_PUBLIC);

        foreach ($properties as $property) {
            if (isset($this->{$property->getName()})) {
                $value = $this->{$property->getName()};

                if ($value instanceof Entity) {
                    $values[$property->getName()] = $value->toArray();
                } elseif (is_array($value) && array_is_list($value) && $this->isArrayWithEntities($value)) {
                    $values[$property->getName()] = [];

                    for ($i = 0; $i < count($value); $i++) {
                        /**
                         * @var Entity $value
                         */
                        $values[$property->getName()][] = $value[$i]->toArray();
                    }
                } elseif ($this->isEnum($property)) {
                    $values[$property->getName()] = $value?->value;
                } else {
                    $values[$property->getName()] = $value;
                }
            }
        }

        return $values;
    }

    /**
     * Установка значений через массив.
     *
     * @param array $values Массив свойств и значений.
     *
     * @return $this
     * @throws ParameterInvalidException
     */
    public function set(array $values): self
    {
        $reflection = new ReflectionClass($this);

        foreach ($values as $property => $value) {
            if ($reflection->hasProperty($property)) {
                $property = $reflection->getProperty($property);

                if ($property->isPublic()) {
                    if ($this->hasType($property, 'Carbon\Carbon')) {
                        if ($value) {
                            $this->{$property->getName()} = Carbon::parse($value);
                        } else {
                            $this->{$property->getName()} = null;
                        }
                    } elseif ($this->hasType($property, 'Illuminate\Http\UploadedFile')) {
                        $this->{$property->getName()} = $value;
                    } elseif ($this->isEnum($property)) {
                        $this->{$property->getName()} = $value ? $this->getTypeEnum($property, $value) : null;
                    } elseif ($this->isType($property, Entity::class)) {
                        /**
                         * @var Entity $entity
                         */
                        $entity = $this->getTypeEntity($property);

                        if (!$entity) {
                            throw new ParameterInvalidException(
                                trans('entity.entityNotExist', ['parameter' => $property->getName()])
                            );
                        }

                        if ($value) {
                            $entity = new $entity($value);
                            $this->{$property->getName()} = $entity;
                        } else {
                            $this->{$property->getName()} = null;
                        }
                    } elseif (is_array($value) && $property->getAttributes()) {
                        $entity = $this->getEntityAttribute($property);

                        if (!$entity) {
                            throw new ParameterInvalidException(
                                trans('entity.entityNotExist', ['parameter' => $property->getName()])
                            );
                        }

                        if (is_string($entity)) {
                            $entities = [];

                            for ($i = 0; $i < count($value); $i++) {
                                if ($value[$i]) {
                                    /**
                                     * @var Entity $ent
                                     */
                                    $entities[$i] = new $entity($value[$i]);
                                }
                            }

                            $this->{$property->getName()} = $entities;
                        } else {
                            $entityFieldNameClass = $this->getEntityFieldNameClass($property);

                            $entity = ($entity[$this->{$entityFieldNameClass}]);
                            $this->{$property->getName()} = new $entity($value);
                        }
                    } else {
                        $this->{$property->getName()} = $value;
                    }
                }
            }
        }

        return $this;
    }

    /**
     * Перевод массива значений в массив сущностей.
     *
     * @param array $items Массив свойств и значений.
     * @param Entity $entity Сущность для перевода.
     *
     * @return Entity[] Массив сущностей
     * @throws ParameterInvalidException
     */
    public static function toEntities(array $items, Entity $entity): array
    {
        $result = [];

        foreach ($items as $item) {
            $result[] = clone $entity->set($item);
        }

        return $result;
    }

    /**
     * Вернет параметры аннотации свойства сущности.
     *
     * @param ReflectionProperty $property Название свойства.
     * @param int $index Индекс параметра.
     *
     * @return string|array|null Параметр.
     */
    private function getEntityParameter(ReflectionProperty $property, int $index): string|array|null
    {
        $attributes = $property->getAttributes();

        foreach ($attributes as $attribute) {
            if ($attribute->getName() === 'App\Models\Entities') {
                $arguments = $attribute->getArguments();

                if (!empty($arguments[$index])) {
                    return $arguments[$index];
                }
            }
        }

        return null;
    }

    /**
     * Вернет сущность, которая хранится в аннотации свойства сущности.
     *
     * @param ReflectionProperty $property Название свойства.
     *
     * @return string|array|null Вернет название класса сущности.
     */
    private function getEntityAttribute(ReflectionProperty $property): string|array|null
    {
        return $this->getEntityParameter($property, 0);
    }

    /**
     * Вернет название поля, которое хранится в аннотации свойства сущности.
     *
     * @param ReflectionProperty $property Название свойства.
     *
     * @return string|null Вернет название класса сущности.
     */
    private function getEntityFieldNameClass(ReflectionProperty $property): ?string
    {
        return $this->getEntityParameter($property, 1);
    }

    /**
     * Проверка на существование типа.
     *
     * @param ReflectionProperty $property Свойство.
     * @param string $nameType Название типа.
     *
     * @return bool Вернет результат проверки.
     */
    private function hasType(ReflectionProperty $property, string $nameType): bool
    {
        $types = $this->getTypes($property);

        if (in_array($nameType, $types, true)) {
            return true;
        }

        return false;
    }

    /**
     * Проверка типа на соответствие определенному классу.
     *
     * @param ReflectionProperty $property Свойство.
     * @param string $class Класс для проверки.
     *
     * @return bool Вернет результат проверки.
     * @throws ParameterInvalidException
     */
    private function isType(ReflectionProperty $property, string $class): bool
    {
        $types = $this->getTypes($property);

        if ($types) {
            foreach ($types as $type) {
                if (class_exists($type) && new $type() instanceof $class) {
                    return true;
                }
            }

            return false;
        }

        throw new ParameterInvalidException(
            trans('entity.typeNotExist', ['parameter' => get_class($this) . '::' . $property->getName()])
        );
    }

    /**
     * Проверка типа, что он Enum.
     *
     * @param ReflectionProperty $property Свойство.
     *
     * @return bool Вернет результат проверки.
     * @throws ParameterInvalidException
     */
    private function isEnum(ReflectionProperty $property): bool
    {
        $types = $this->getTypes($property);

        if ($types) {
            foreach ($types as $type) {
                if (enum_exists($type)) {
                    return true;
                }
            }

            return false;
        }

        throw new ParameterInvalidException(
            trans('entity.typeNotExist', ['parameter' => get_class($this) . '::' . $property->getName()])
        );
    }

    /**
     * Вернет сущность, которая хранится в типе свойства.
     *
     * @param ReflectionProperty $property Свойство.
     *
     * @return Entity|null Сущность.
     * @throws Exception
     */
    private function getTypeEntity(ReflectionProperty $property): ?Entity
    {
        $types = $this->getTypes($property);

        $entityFieldNameClass = $this->getEntityFieldNameClass($property);

        if ($entityFieldNameClass) {
            $entityAttributes = $this->getEntityAttribute($property);

            if (isset($entityAttributes[$this->$entityFieldNameClass])) {
                $entityBelongs = $entityAttributes[$this->$entityFieldNameClass];

                foreach ($types as $type) {
                    if (new $type() instanceof $entityBelongs) {
                        return new $type();
                    }
                }
            } else {
                throw new Exception('entity.typeNotExist');
            }
        } else {
            foreach ($types as $type) {
                if (new $type() instanceof Entity) {
                    return new $type();
                }
            }
        }

        return null;
    }

    /**
     * Вернет Enum, который хранится в типе свойства.
     *
     * @param ReflectionProperty $property Свойство.
     * @param string|int|float $value Значение.
     *
     * @return object|null Enum.
     */
    private function getTypeEnum(ReflectionProperty $property, string|int|float $value): ?object
    {
        $types = $this->getTypes($property);

        foreach ($types as $type) {
            if (enum_exists($type)) {
                return $type::from($value);
            }
        }

        return null;
    }

    /**
     * Получение всех типов данных для свойства.
     *
     * @param ReflectionProperty $property Свойство.
     *
     * @return array Вернет все типы данных.
     */
    private function getTypes(ReflectionProperty $property): array
    {
        $type = $property->getType();

        if ($type instanceof ReflectionUnionType) {
            $types = $type->getTypes();
            $result = [];

            if ($types) {
                foreach ($types as $type) {
                    $result[] = $type->getName();
                }
            }

            return $result;
        } elseif ($type) {
            return [$type->getName()];
        }

        return [];
    }

    /**
     * Проверка является ли данный массив массивом сущностей.
     *
     * @param array $checkArray Проверяемый массив.
     * @return bool Результат проверки.
     */
    private function isArrayWithEntities(array $checkArray): bool
    {
        $status = true;

        for ($i = 0; $i < count($checkArray); $i++) {
            if (!is_object($checkArray[$i]) || !method_exists($checkArray[$i], 'toArray')) {
                $status = false;

                break;
            }
        }

        return $status;
    }
}
