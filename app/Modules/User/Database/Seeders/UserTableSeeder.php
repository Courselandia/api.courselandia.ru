<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Database\Seeders;

use DB;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

/**
 * Класс наполнения начальными данными: пользователь.
 */
class UserTableSeeder extends Seeder
{
    /**
     * Запуск наполнения начальными данными.
     *
     * @return void
     */
    public function run(): void
    {
        DB::table('users')->delete();

        DB::table('users')->insert(array(
            0 => array(
                'id' => 1,
                'login' => 'admin@courselandia.ru',
                'password' => bcrypt('admin'),
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => null
            ),
            1 => array(
                'id' => 2,
                'login' => 'manager@courselandia.ru',
                'password' => bcrypt('manager'),
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => null
            ),
            2 => array(
                'id' => 3,
                'login' => 'user@courselandia.ru',
                'password' => bcrypt('user'),
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => null
            ),
            3 => array(
                'id' => 4,
                'login' => 'unverified@courselandia.ru',
                'password' => bcrypt('unverified'),
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => null
            ),
        ));
    }
}
