<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Класс миграции.
 */
class CreateTableUserAuths extends Migration
{
    /**
     * Запуск миграции.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('user_auths', function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();
            $table->bigInteger('user_id')->unsigned()->index();

            $table->string('os', 191)->nullable();
            $table->string('device', 191)->nullable();
            $table->string('browser', 191)->nullable();
            $table->string('agent', 1000)->nullable();
            $table->string('ip', 191)->nullable();
            $table->float('latitude', 15, 10)->nullable();
            $table->float('longitude', 15, 10)->nullable();
            $table->string('country_code', 191)->nullable();
            $table->string('region_code', 191)->nullable();
            $table->string('city', 191)->nullable();
            $table->string('zip', 191)->nullable();

            $table->timestamps();
            $table->softDeletes()->index();
        });
    }

    /**
     * Запуск отката миграции.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::drop('user_auths');
    }
}
