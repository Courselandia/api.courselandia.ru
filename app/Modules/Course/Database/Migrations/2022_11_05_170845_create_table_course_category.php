<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Класс миграции.
 */
class CreateTableCourseCategory extends Migration
{
    /**
     * Запуск миграции.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('course_category', function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();
            $table->bigInteger('course_id')->unsigned()->index('course_id');
            $table->bigInteger('category_id')->unsigned()->index('category_id');
        });
    }

    /**
     * Запуск отката миграции.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::drop('course_category');
    }
}
