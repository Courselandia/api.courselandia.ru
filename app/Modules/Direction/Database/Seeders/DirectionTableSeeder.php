<?php
/**
 * Модуль Направления.
 * Этот модуль содержит все классы для работы с направлениями.
 *
 * @package App\Modules\Direction
 */

namespace App\Modules\Direction\Database\Seeders;

use DB;
use Util;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

/**
 * Класс наполнения начальными данными: направления.
 */
class DirectionTableSeeder extends Seeder
{
    /**
     * Запуск наполнения начальными данными.
     *
     * @return void
     */
    public function run(): void
    {
        DB::table('directions')->delete();

        DB::table('directions')->insert([
            [
                'id' => 1,
                'name' => 'Программирование',
                'header' => 'Онлайн курсы по Программированию',
                'weight' => 1,
                'link' => Util::latin('программирование'),
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 2,
                'name' => 'Маркетинг',
                'header' => 'Онлайн курсы по Маркетингу',
                'weight' => 2,
                'link' => Util::latin('маркетинг'),
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 3,
                'name' => 'Дизайн',
                'header' => 'Онлайн курсы по Дизайну',
                'weight' => 3,
                'link' => Util::latin('дизайн'),
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 4,
                'name' => 'Бизнес и управление',
                'header' => 'Онлайн курсы по Бизнесу и управлению',
                'weight' => 4,
                'link' => Util::latin('бизнес и управление'),
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 5,
                'name' => 'Аналитика',
                'header' => 'Онлайн курсы по Аналитике',
                'weight' => 5,
                'link' => Util::latin('aналитика'),
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 6,
                'name' => 'Игры',
                'header' => 'Онлайн курсы по Играм',
                'weight' => 6,
                'link' => Util::latin('игры'),
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 7,
                'name' => 'Другие профессии',
                'header' => 'Онлайн курсы по Другим профессиям',
                'weight' => 7,
                'link' => Util::latin('другие профессии'),
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);
    }
}
