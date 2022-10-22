<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Класс миграции.
 */
class CreateTableImages extends Migration
{
    /**
     * Запуск миграции.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('images', function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();
            $table->binary('byte')->nullable();
            $table->string('format', 6);
            $table->string('folder', 191);
            $table->string('cache', 15)->nullable();
            $table->smallInteger('width')->unsigned()->nullable();
            $table->smallInteger('height')->unsigned()->nullable();

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
        Schema::drop('images');
    }
}
