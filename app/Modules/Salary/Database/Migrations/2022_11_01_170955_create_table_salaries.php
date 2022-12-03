<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Класс миграции.
 */
class CreateTableSalaries extends Migration
{
    /**
     * Запуск миграции.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('salaries', function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();
            $table->bigInteger('profession_id')->unsigned()->index('profession_id');

            $table->string('level', 191);
            $table->integer('salary')->unsigned();

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
        Schema::drop('salaries');
    }
}
