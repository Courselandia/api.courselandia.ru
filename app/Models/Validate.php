<?php
/**
 * Ядро базовых классов.
 * Этот пакет содержит ядро базовых классов для работы с основными компонентами и возможностями системы.
 *
 * @package App.Models
 */

namespace App\Models;

use Validator;
use App\Models\Exceptions\ValidateException;

/**
 * Трейт валидирования модели.
 * Данный трет позволяет расширить возможности модели для автоматической валидации при добавлении информации.
 */
trait Validate
{
    /**
     * Метод, который должен вернуть все правила валидации.
     *
     * @return array Массив правил валидации для этой модели.
     */
    abstract protected function getRules(): array;

    /**
     * Метод, который должен вернуть все названия атрибутов.
     *
     * @return array Массив названий атрибутов.
     */
    abstract protected function getNames(): array;

    /**
     * Метод, который должен вернуть все сообщения об ошибках.
     *
     * @return array Массив возможных ошибок валидации.
     */
    protected function getMessages(): array
    {
        return [];
    }

    /**
     * Обработчик загрузки.
     * Предназначен для настройки обработчика при сохранении.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $status = $model->validate();

            if (!$status) {
                return false;
            } else {
                return true;
            }
        });
    }

    /**
     * Валидирование текущих атрибутов.
     *
     * @return bool Если валидация прошла успешно вернет true.
     * @throws ValidateException
     */
    public function validate(): bool
    {
        $attributes = $this->getAttributes();

        foreach ($attributes as $k => $v) {
            if (is_null($v)) {
                unset($attributes[$k]);
            }
        }

        $val = Validator::make($attributes, static::getRules(), static::getMessages(), static::getNames());

        if ($val->passes()) {
            return true;
        }

        $errors = $val->messages()->toArray();

        foreach ($errors as $key => $value) {
            for ($i = 0; $i < count($value); $i++) {
                throw new ValidateException($value[$i], $key);
            }
        }

        return false;
    }
}
