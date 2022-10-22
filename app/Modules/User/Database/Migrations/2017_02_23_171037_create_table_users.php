<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Класс миграции.
 */
class CreateTableUsers extends Migration
{
    /**
     * Запуск миграции.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();
            $table->string('image_small_id')->nullable()->index();
            $table->string('image_middle_id')->nullable();
            $table->string('image_big_id')->nullable();
            $table->string('login')->index();
            $table->string('password')->index()->nullable();
            $table->string('remember_token', 100)->nullable()->index();
            $table->string('first_name', 191)->nullable();
            $table->string('second_name', 191)->nullable();
            $table->string('phone', 30)->nullable();
            $table->boolean('two_factor')->default(0)->index();
            $table->json('flags')->nullable();
            $table->boolean('status')->default(true)->index();

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
        Schema::drop('users');
    }
}
