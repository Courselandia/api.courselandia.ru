<?php
/**
 * Модуль Запоминания действий.
 * Этот модуль содержит все классы для работы с запоминанием и контролем действий пользователя.
 *
 * @package App\Modules\Act
 */

namespace App\Modules\Act\Tests\Feature\Models;

use Act;
use Tests\TestCase;

/**
 * Тестирование: Система запоминания действия.
 */
class ImplementTest extends TestCase
{
    /**
     * Название действия.
     *
     * @var string
     */
    private string $name = 'test';

    /**
     * Проверка на статус на возможность сделать действие.
     *
     * @return void
     */
    public function testStatusValid(): void
    {
        Act::add($this->name);
        Act::add($this->name);
        Act::add($this->name);

        $this->assertTrue(Act::status($this->name, 5) === true, 'The assertion has to be true but got false.');

        Act::delete($this->name);
    }

    /**
     * Проверка на статуса в случаи невозможности повторного действия.
     *
     * @return void
     */
    public function testStatusInvalid(): void
    {
        Act::add($this->name);
        Act::add($this->name);
        Act::add($this->name);

        $this->assertTrue(Act::status($this->name, 2) === false, 'The assertion has to be true but got false.');

        Act::delete($this->name);
    }

    /**
     * Проверка на добавление.
     *
     * @return void
     */
    public function testAdd(): void
    {
        Act::add($this->name);

        $this->assertTrue(Act::get($this->name) === 1, 'The assertion has to be true but got false.');

        Act::delete($this->name);
    }

    /**
     * Проверка на добавление.
     *
     * @return void
     */
    public function testDelete(): void
    {
        Act::add($this->name);
        Act::add($this->name);
        Act::add($this->name);
        Act::delete($this->name);

        $this->assertTrue(Act::get($this->name) === 0, 'The assertion has to be true but got false.');

        Act::delete($this->name);
    }
}
