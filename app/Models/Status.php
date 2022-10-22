<?php
/**
 * Ядро базовых классов.
 * Этот пакет содержит ядро базовых классов для работы с основными компонентами и возможностями системы.
 *
 * @package App.Models
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

/**
 * Трейт для модели которая поддерживает систему статусов.
 */
trait Status
{
    /**
     * Проверка статуса.
     *
     * @param  Builder  $query  Запрос.
     * @param  bool  $status  Статус активности.
     *
     * @return Builder Построитель запросов.
     */
    private function statusIs(Builder $query, bool $status = true): Builder
    {
        $query->where($this->getTable().'.status', $status);

        return $query;
    }

    /**
     * Заготовка запроса активных записей.
     *
     * @param  Builder  $query  Запрос.
     *
     * @return Builder Построитель запросов.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $this->statusIs($query);
    }

    /**
     * Заготовка запроса не активных записей.
     *
     * @param  Builder  $query  Запрос.
     *
     * @return Builder Построитель запросов.
     */
    public function scopeNotActive(Builder $query): Builder
    {
        return $this->statusIs($query, false);
    }

    /**
     * Проверить статус
     *
     * @return bool Вернет статус.
     */
    public function statusCheck(): bool
    {
        return (bool)$this->status;
    }
}
