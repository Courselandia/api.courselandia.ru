<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Класс миграции.
 */
class CreateTableCategoryProfession extends Migration
{
    /**
     * Запуск миграции.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('category_profession', function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();
            $table->bigInteger('category_id')->unsigned()->index('category_id');
            $table->bigInteger('profession_id')->unsigned()->index('profession_id');
        });
    }

    /**
     * Запуск отката миграции.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::drop('category_direction');
    }
}
