<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Tests\Feature\Http\Controllers;

use App\Modules\User\Models\User;
use App\Modules\User\Models\UserRole;
use App\Modules\User\Models\UserVerification;
use Throwable;
use Tests\TestCase;
use Faker\Factory as Faker;
use App\Models\Fakers\PhoneFaker;
use App\Models\Test\TokenTest;

/**
 * Тестирование: Класс контроллер для авторизации и аутентификации.
 */
class AccessControllerTest extends TestCase
{
    use TokenTest;

    /**
     * Получение данных авторизованного пользователя.
     *
     * @return void
     */
    public function testAdminGate(): void
    {
        $this->json(
            'GET',
            'api/private/access/gate',
            [],
            [
                'Authorization' => 'Bearer '.$this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => $this->getGateStructure()
        ]);
    }

    /**
     * Получение данных авторизованного пользователя с ошибкой.
     *
     * @return void
     */
    public function testAdminGateNotValid(): void
    {
        $this->json(
            'GET',
            'api/private/access/gate',
            [],
            [
                'Authorization' => '123',
                'Accept' => 'application/json',
                'X-Requested-With' => 'XMLHttpRequest'
            ]
        )->assertStatus(401)->assertJsonStructure([
            'success',
            'message'
        ]);
    }

    /**
     * Выход пользователя.
     *
     * @return void
     */
    public function testLogout(): void
    {
        $this->json(
            'POST',
            'api/private/access/logout',
            [],
            [
                'Authorization' => 'Bearer '.$this->getAdminToken()
            ]
        )->assertStatus(200)->assertJson([
            'success' => true
        ]);
    }

    /**
     * Регистрация или вход через социальную сеть.
     *
     * @return void
     */
    public function testSocial(): void
    {
        $faker = Faker::create();

        $this->json(
            'POST',
            'api/private/site/access/social',
            [
                'uid' => $faker->uuid,
                'login' => $faker->email,
                'social' => 'facebook',
                'first_name' => $faker->firstName,
                'second_name' => $faker->lastName
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => [
                'user' => $this->getGateStructure(),
                'secret',
                'accessToken',
                'refreshToken',
            ],
        ]);
    }

    /**
     * Регистрация пользователя.
     *
     * @return void
     */
    public function testSignUp(): void
    {
        $faker = Faker::create();
        $faker->addProvider(new PhoneFaker($faker));
        $password = $faker->password;

        $this->json(
            'POST',
            'api/private/site/access/sign-up',
            [
                'login' => $faker->email,
                'password' => $password,
                'password_confirmation' => $password,
                'first_name' => $faker->firstName,
                'second_name' => $faker->lastName,
                'phone' => $faker->phone(7)
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => [
                'user' => $this->getGateStructure(),
                'secret',
                'accessToken',
                'refreshToken'
            ]
        ]);
    }

    /**
     * Регистрация пользователя с ошибкой при неверном вводе логина.
     *
     * @return void
     */
    public function testSignUpNotValidLogin(): void
    {
        $faker = Faker::create();
        $faker->addProvider(new PhoneFaker($faker));

        $this->json(
            'POST',
            'api/private/site/access/sign-up',
            [
                'login' => 'login',
                'password' => $this->getAdmin('password'),
                'password_confirmation' => $this->getAdmin('password'),
                'first_name' => $faker->firstName,
                'second_name' => $faker->lastName,
                'phone' => $faker->phone(7)
            ]
        )->assertStatus(400)->assertJsonStructure([
            'success',
            'message'
        ]);
    }

    /**
     * Регистрация пользователя с ошибкой если пользователь уже существует.
     *
     * @return void
     */
    public function testSignUpNotValidExist(): void
    {
        $faker = Faker::create();
        $faker->addProvider(new PhoneFaker($faker));
        $password = $faker->password;

        $this->json(
            'POST',
            'api/private/site/access/sign-up',
            [
                'login' => $this->getAdmin('login'),
                'password' => $password,
                'password_confirmation' => $password,
                'first_name' => $faker->firstName,
                'second_name' => $faker->lastName,
                'phone' => $faker->phone(7)
            ]
        )->assertStatus(400)->assertJsonStructure([
            'success',
            'message'
        ]);
    }

    /**
     * Авторизация пользователя.
     *
     * @return void
     */
    public function testSignIn(): void
    {
        $this->json(
            'POST',
            'api/private/site/access/sign-in',
            [
                'login' => $this->getAdmin('login'),
                'password' => $this->getAdmin('password'),
                'remember' => true
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => [
                'user' => $this->getGateStructure(),
                'secret',
                'accessToken',
                'refreshToken'
            ]
        ]);
    }

    /**
     * Авторизация пользователя с ошибкой.
     *
     * @return void
     */
    public function testSignInNotValid(): void
    {
        $this->json(
            'POST',
            'api/private/site/access/sign-in',
            [
                'login' => $this->getUnknownUser('login'),
                'password' => $this->getUnknownUser('password'),
                'remember' => true
            ]
        )->assertStatus(401)->assertJsonStructure([
            'success',
            'message'
        ]);
    }

    /**
     * Отправка e-mail сообщения на верификацию.
     *
     * @return void
     */
    public function testVerify(): void
    {
        $this->json(
            'POST',
            'api/private/site/access/verify',
            [],
            [
                'Authorization' => 'Bearer '.$this->getUnverifiedToken()
            ]
        )->assertStatus(200)->assertJson([
            'success' => true
        ]);
    }

    /**
     * Отправка e-mail сообщения на верификацию с ошибкой.
     *
     * @return void
     */
    public function testVerifyNotValid(): void
    {
        $this->json(
            'POST',
            'api/private/site/access/verify',
            [],
            [
                'Authorization' => 'Bearer '.$this->getAdminToken()
            ]
        )->assertStatus(400)->assertJsonStructure([
            'success',
            'message'
        ]);
    }

    /**
     * Верификация пользователя.
     *
     * @return void
     * @throws Throwable
     */
    public function testToVerify(): void
    {
        $faker = Faker::create();
        $faker->addProvider(new PhoneFaker($faker));
        $password = $faker->password;

        $content = $this->json(
            'POST',
            'api/private/site/access/sign-up',
            [
                'login' => $faker->email,
                'password' => $password,
                'password_confirmation' => $password,
                'first_name' => $faker->firstName,
                'second_name' => $faker->lastName,
                'phone' => $faker->phone(7)
            ]
        )->decodeResponseJson();

        $this->json(
            'POST',
            'api/private/site/access/verify/'.$content['data']['user']['id'],
            [
                'code' => $faker->name
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => [
                'user' => $this->getGateStructure(),
                'secret',
                'accessToken',
                'refreshToken'
            ]
        ]);
    }

    /**
     * Отправка e-mail для восстановления пароля.
     *
     * @return void
     */
    public function testForget(): void
    {
        $this->json(
            'POST',
            'api/private/site/access/forget',
            [
                'login' => $this->getUser('login'),
            ]
        )->assertStatus(200)->assertJson([
            'success' => true
        ]);
    }

    /**
     * Отправка e-mail для восстановления пароля с ошибкой.
     *
     * @return void
     */
    public function testForgetNotValid(): void
    {
        $this->json(
            'POST',
            'api/private/site/access/forget',
            [
                'login' => $this->getUnknownUser('login'),
            ]
        )->assertStatus(400)->assertJsonStructure([
            'success',
            'message'
        ]);
    }

    /**
     * Проверка возможности сбить пароль.
     *
     * @return void
     * @throws Throwable
     */
    public function testResetCheck(): void
    {
        $faker = Faker::create();
        $faker->addProvider(new PhoneFaker($faker));
        $password = $faker->password;
        $email = $faker->email;

        $content = $this->json(
            'POST',
            'api/private/site/access/sign-up',
            [
                'login' => $email,
                'password' => $password,
                'password_confirmation' => $password,
                'first_name' => $faker->firstName,
                'second_name' => $faker->lastName,
                'phone' => $faker->phone(7)
            ]
        )->decodeResponseJson();

        $this->json(
            'POST',
            'api/private/site/access/forget',
            [
                'login' => $email,
            ]
        );

        $this->json(
            'GET',
            'api/private/site/access/reset-check/'.$content['data']['user']['id'],
            [
                'code' => $faker->name,
            ]
        )->assertJson([
            'success' => true
        ]);
    }

    /**
     * Установка нового пароля.
     *
     * @return void
     * @throws Throwable
     */
    public function testReset(): void
    {
        $faker = Faker::create();
        $faker->addProvider(new PhoneFaker($faker));
        $password = $faker->password;
        $email = $faker->email;

        $content = $this->json(
            'POST',
            'api/private/site/access/sign-up',
            [
                'login' => $email,
                'password' => $password,
                'password_confirmation' => $password,
                'first_name' => $faker->firstName,
                'second_name' => $faker->lastName,
                'phone' => $faker->phone(7)
            ]
        )->decodeResponseJson();

        $this->json(
            'POST',
            'api/private/site/access/forget',
            [
                'login' => $email,
            ]
        );

        $this->json(
            'POST',
            'api/private/site/access/reset/'.$content['data']['user']['id'],
            [
                'code' => $faker->name,
                'password' => $password,
                'password_confirmation' => $password
            ]
        )->assertJson([
            'success' => true
        ]);
    }

    /**
     * Обновление данных.
     *
     * @return void
     */
    public function testUpdate(): void
    {
        $faker = Faker::create();
        $faker->addProvider(new PhoneFaker($faker));

        $this->json(
            'PUT',
            'api/private/site/access/update',
            [
                'first_name' => $faker->firstName,
                'second_name' => $faker->lastName,
                'phone' => $faker->phone(7),
                'two_factor' => false
            ],
            [
                'Authorization' => 'Bearer '.$this->getUserToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => $this->getGateStructure(),
        ]);
    }

    /**
     * Изменение пароля.
     *
     * @return void
     */
    public function testPassword(): void
    {
        $this->json(
            'PUT',
            'api/private/site/access/password',
            [
                'password_current' => $this->getUser('password'),
                'password' => $this->getUser('password'),
                'password_confirmation' => $this->getUser('password')
            ],
            [
                'Authorization' => 'Bearer '.$this->getUserToken()
            ]
        )->assertStatus(200)->assertJson([
            'success' => true
        ]);
    }

    /**
     * Изменение пароля с ошибкой.
     *
     * @return void
     */
    public function testPasswordNotValid(): void
    {
        $this->json(
            'PUT',
            'api/private/site/access/password',
            [
                'password_current' => '123456',
                'password' => $this->getUser('password'),
                'password_confirmation' => $this->getUser('password')
            ],
            [
                'Authorization' => 'Bearer '.$this->getUserToken()
            ]
        )->assertStatus(400)->assertJsonStructure([
            'success',
            'message'
        ]);
    }

    /**
     * Проверка пользователя с полной информацией.
     *
     * @return void
     */
    public function testUserFull(): void
    {
        $user = User::factory()->create();

        UserRole::factory()->create([
            'user_id' => $user->id
        ]);

        UserVerification::factory()->create([
            'user_id' => $user->id
        ]);

        $this->json(
            'GET',
            'api/private/access/gate',
            [],
            [
                'Authorization' => 'Bearer '.$this->getToken($user->login, 'password')
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => $this->getGateStructure()
        ]);
    }

    /**
     * Получение структуры гейта.
     *
     * @return array Вернет структуру гейта.
     */
    private function getGateStructure(): array
    {
        return [
            'id',
            'image_small_id',
            'image_middle_id',
            'login',
            'password',
            'remember_token',
            'first_name',
            'second_name',
            'phone',
            'two_factor',
            'status',
            'flags',
            'recovery',
            'verification' => [
                'id',
                'user_id',
                'code',
                'status',
                'created_at',
                'updated_at',
                'deleted_at',
            ],
            'role' => [
                'id',
                'user_id',
                'name',
                'created_at',
                'updated_at',
                'deleted_at'
            ],
            'created_at',
            'updated_at',
            'deleted_at'
        ];
    }
}
