<?php
/**
 * Модуль Запоминания действий.
 * Этот модуль содержит все классы для работы с запоминанием и контролем действий пользователя.
 *
 * @package App\Modules\Act
 */

namespace App\Modules\Act\Tests\Feature\Http\Middleware;

use Act;
use App\Models\Exceptions\RecordNotExistException;
use ReflectionException;
use Tests\TestCase;
use Illuminate\Http\Request;
use App\Modules\Act\Http\Middleware\AllowAct;

/**
 * Тестирование: Класс посредник для проверки доступа.
 */
class AllowMiddlewareTest extends TestCase
{
    /**
     * Название действия.
     *
     * @var string
     */
    private string $name = 'test';

    /**
     * Проверка пользователя, что он может выполнить действие.
     *
     * @return void
     * @throws ReflectionException
     * @throws RecordNotExistException
     */
    public function testAct(): void
    {
        $request = new Request();
        $mw = new AllowAct();

        Act::add($this->name)
            ->add($this->name);

        $request = $mw->handle(
            $request,
            function ($request) {
                $this->assertInstanceOf(Request::class, $request);

                return $request;
            },
            $this->name,
            5
        );

        if (!$request instanceof Request) {
            $this->fail();
        }

        $this->assertTrue(true);

        Act::delete($this->name);
    }

    /**
     * Проверка пользователя, что он может выполнить действие с ошибкой.
     *
     * @return void
     * @throws RecordNotExistException|ReflectionException
     */
    public function testActNotValid(): void
    {
        $request = new Request();
        $mw = new AllowAct();

        Act::add($this->name);
        Act::add($this->name);
        Act::add($this->name);

        Act::add($this->name)
            ->add($this->name);

        $response = $mw->handle(
            $request,
            function ($response) {
                return $response;
            },
            $this->name,
            2
        );

        $this->assertTrue(
            $response->getStatusCode() === 401,
            'The status code has to be 401 but got ' . $response->getStatusCode() . '.'
        );

        Act::delete($this->name);
    }
}
