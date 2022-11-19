<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Класс миграции.
 */
class CreateTableActs extends Migration
{
    /**
     * Запуск миграции.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('acts', function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();
            $table->string('index', 191)->index();
            $table->bigInteger('count')->unsigned();
            $table->bigInteger('minutes')->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Запуск отката миграции.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::drop('acts');
    }
}
