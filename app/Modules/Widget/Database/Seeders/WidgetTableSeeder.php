<?php
/**
 * Модуль Виджетов.
 * Этот модуль содержит все классы для работы с виджетами, которые можно использовать в публикациях.
 *
 * @package App\Modules\Widget
 */

namespace App\Modules\Widget\Database\Seeders;

use DB;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

/**
 * Класс наполнения начальными данными: виджеты.
 */
class WidgetTableSeeder extends Seeder
{
    /**
     * Запуск наполнения начальными данными.
     *
     * @return void
     */
    public function run(): void
    {
        DB::table('widgets')->delete();

        DB::table('widgets')->insert(array(
            0 => array(
                'id' => 1,
                'name' => 'Публикации :: Читайте так же',
                'index' => 'publications-also',
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => null
            ),
            1 => array(
                'id' => 2,
                'login' => 'Коллекции :: Смотрите так же',
                'password' => 'collections-also',
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => null
            ),
        ));
    }
}
