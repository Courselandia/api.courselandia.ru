<?php
/**
 * Модуль Школ.
 * Этот модуль содержит все классы для работы со школами.
 *
 * @package App\Modules\School
 */

namespace App\Modules\School\Database\Seeders;

use DB;
use Util;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

/**
 * Класс наполнения начальными данными: школы.
 */
class SchoolTableSeeder extends Seeder
{
    /**
     * Запуск наполнения начальными данными.
     *
     * @return void
     */
    public function run(): void
    {
        DB::table('schools')->delete();

        DB::table('schools')->insert([
            [
                'id' => 1,
                'name' => 'Нетология',
                'header' => 'Онлайн-курсы школы Нетологии',
                'header_template' => 'Онлайн-курсы школы {school:genitive}',
                'link' => Util::latin('нетология'),
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 2,
                'name' => 'Skillbox',
                'header' => 'Онлайн-курсы школы Skillbox',
                'header_template' => 'Онлайн-курсы школы {school:genitive}',
                'link' => 'skillbox',
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 3,
                'name' => 'GeekBrains',
                'header' => 'Онлайн-курсы школы GeekBrains',
                'header_template' => 'Онлайн-курсы школы {school:genitive}',
                'link' => 'geekbrains',
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);
    }
}
