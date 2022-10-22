<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Класс миграции.
 */
class CreateTableAlerts extends Migration
{
    /**
     * Запуск миграции.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('alerts', function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();

            $table->string('title', 191)->nullable();
            $table->string('description', 1000)->nullable();
            $table->string('url', 191)->nullable();
            $table->string('tag', 50)->nullable();
            $table->string('color', 50)->nullable();
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
        Schema::drop('alerts');
    }
}
