<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Database\Seeders;

use DB;
use Illuminate\Database\Seeder;
use Crypt;
use Carbon\Carbon;
use App\Modules\User\Entities\UserVerification;

/**
 * Класс наполнения начальными данными: верификация пользователя.
 */
class UserVerificationTableSeeder extends Seeder
{
    /**
     * Запуск наполнения начальными данными.
     *
     * @return void
     */
    public function run(): void
    {
        DB::table('user_verifications')->delete();

        DB::table('user_verifications')->insert(array(
            0 => array(
                'id' => 1,
                'user_id' => 1,
                'code' => UserVerification::generateCode(1),
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => null
            ),
            1 => array(
                'id' => 2,
                'user_id' => 2,
                'code' => UserVerification::generateCode(2),
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => null
            ),
            2 => array(
                'id' => 3,
                'user_id' => 3,
                'code' => UserVerification::generateCode(3),
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => null
            ),
            3 => array(
                'id' => 4,
                'user_id' => 4,
                'code' => UserVerification::generateCode(4),
                'status' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => null
            ),
        ));
    }
}
