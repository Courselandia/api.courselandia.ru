<?php

namespace Database\Seeders;

use App\Modules\User\Database\Seeders\UserDatabaseSeeder;
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

        Model::reguard();
    }
}
