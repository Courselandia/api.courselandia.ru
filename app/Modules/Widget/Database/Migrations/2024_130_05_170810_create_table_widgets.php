<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Класс миграции.
 */
class CreateTableWidgets extends Migration
{
    /**
     * Запуск миграции.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('widgets', function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();

            $table->string('name', 191)->index('name');
            $table->string('index', 191)->index('index');

            $table->boolean('status')->default(1)->index();

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
        Schema::drop('widgets');
    }
}
