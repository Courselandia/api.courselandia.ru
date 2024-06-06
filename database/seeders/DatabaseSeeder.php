<?php

namespace Database\Seeders;

use App\Modules\Direction\Database\Seeders\DirectionTableSeeder;
use App\Modules\School\Database\Seeders\SchoolTableSeeder;
use App\Modules\User\Database\Seeders\UserDatabaseSeeder;
use App\Modules\Widget\Database\Seeders\WidgetDatabaseSeeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        Model::unguard();

        $this->call(UserDatabaseSeeder::class);
        $this->call(DirectionTableSeeder::class);
        $this->call(SchoolTableSeeder::class);
        $this->call(WidgetDatabaseSeeder::class);

        Model::reguard();
    }
}
