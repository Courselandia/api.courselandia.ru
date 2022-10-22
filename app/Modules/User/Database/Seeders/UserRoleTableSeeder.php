<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Database\Seeders;

use App\Modules\User\Enums\Role;
use DB;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

/**
 * Класс наполнения начальными данными ролей по умолчанию.
 */
class UserRoleTableSeeder extends Seeder
{
    /**
     * Запуск наполнения начальными данными.
     *
     * @return void
     */
    public function run(): void
    {
        DB::table('user_roles')->delete();

        DB::table('user_roles')->insert(array(
            0 => array(
                'id' => 1,
                'user_id' => 1,
                'name' => Role::ADMIN,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => null
            ),
            1 => array(
                'id' => 2,
                'user_id' => 2,
                'name' => Role::MANAGER,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => null
            ),
            3 => array(
                'id' => 3,
                'user_id' => 3,
                'name' => Role::USER,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => null
            ),
            4 => array(
                'id' => 4,
                'user_id' => 4,
                'name' => Role::USER,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => null
            ),
        ));
    }
}
