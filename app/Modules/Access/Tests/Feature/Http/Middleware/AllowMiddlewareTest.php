<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Tests\Feature\Http\Middleware;

use Auth;
use Tests\TestCase;
use Illuminate\Http\Request;
use App\Modules\User\Enums\Role;
use App\Modules\Access\Http\Middleware\AllowGuest;
use App\Modules\Access\Http\Middleware\AllowUser;
use App\Modules\Access\Http\Middleware\AllowVerified;
use App\Modules\Access\Http\Middleware\AllowRole;

/**
 * Тестирование: Класс посредник для проверки доступа.
 */
class AllowMiddlewareTest extends TestCase
{
    /**
     * Проверка пользователя, что он гость.
     *
     * @return void
     */
    public function testAllowGuest(): void
    {
        Auth::logout();
        $request = new Request();
        $mw = new AllowGuest();

        $request = $mw->handle(
            $request,
            function ($request) {
                $this->assertInstanceOf(Request::class, $request);

                return $request;
            }
        );

        if(!$request instanceof Request) {
            $this->fail();
        }

        $this->assertTrue(true);
    }

    /**
     * Проверка пользователя, что он гость с ошибкой.
     *
     * @return void
     */
    public function testAllowGuestNotValid(): void
    {
        Auth::loginUsingId(1);
        $request = new Request();
        $mw = new AllowGuest();

        $response = $mw->handle(
            $request,
            function ($response) {
                return $response;
            }
        );

        $this->assertTrue(
            $response->getStatusCode() === 401,
            'The status code has to be 401 but got '.$response->getStatusCode().'.'
        );
    }

    /**
     * Проверка пользователя на авторизацию.
     *
     * @return void
     */
    public function testAllowUser(): void
    {
        Auth::loginUsingId(1);
        $request = new Request();
        $mw = new AllowUser();

        $mw->handle(
            $request,
            function ($request) {
                $this->assertInstanceOf(Request::class, $request);

                return $request;
            }
        );

        if(!$request instanceof Request) {
            $this->fail();
        }

        $this->assertTrue(true);
    }

    /**
     * Проверка пользователя на авторизацию с ошибкой.
     *
     * @return void
     */
    public function testAllowUserNotValid(): void
    {
        Auth::logout();
        $request = new Request();
        $mw = new AllowUser();

        $response = $mw->handle(
            $request,
            function ($response) {
                return $response;
            }
        );

        $this->assertTrue(
            $response->getStatusCode() === 401,
            'The status code has to be 401 but got '.$response->getStatusCode().'.'
        );
    }

    /**
     * Проверка пользователя на верификацию.
     *
     * @return void
     */
    public function testAllowVerified(): void
    {
        Auth::loginUsingId(2);
        $request = new Request();
        $mw = new AllowVerified();

        $mw->handle(
            $request,
            function ($request) {
                $this->assertInstanceOf(Request::class, $request);

                return $request;
            }
        );

        if(!$request instanceof Request) {
            $this->fail();
        }

        $this->assertTrue(true);
    }

    /**
     * Проверка пользователя на верификацию с ошибкой.
     *
     * @return void
     */
    public function testAllowVerifiedNotValid(): void
    {
        Auth::loginUsingId(2);
        $request = new Request();
        $mw = new AllowVerified();

        $response = $mw->handle(
            $request,
            function ($response) {
                return $response;
            },
            false
        );

        $this->assertTrue(
            $response->getStatusCode() === 401,
            'The status code has to be 401 but got '.$response->getStatusCode().'.'
        );
    }

    /**
     * Проверка пользователя на наличие роли.
     *
     * @return void
     */
    public function testAllowRole(): void
    {
        Auth::loginUsingId(1);
        $request = new Request();
        $mw = new AllowRole();

        $mw->handle(
            $request,
            function ($request) {
                $this->assertInstanceOf(Request::class, $request);

                return $request;
            },
            Role::ADMIN->value
        );

        if(!$request instanceof Request) {
            $this->fail();
        }

        $this->assertTrue(true);
    }

    /**
     * Проверка пользователя на наличие роли с ошибкой.
     *
     * @return void
     */
    public function testAllowRoleNotValid(): void
    {
        Auth::loginUsingId(2);
        $request = new Request();
        $mw = new AllowRole();

        $response = $mw->handle(
            $request,
            function ($response) {
                return $response;
            },
            Role::ADMIN->value
        );

        $this->assertTrue(
            $response->getStatusCode() === 401,
            'The status code has to be 401 but got '.$response->getStatusCode().'.'
        );
    }
}
