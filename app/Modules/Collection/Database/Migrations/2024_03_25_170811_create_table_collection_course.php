<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Класс миграции.
 */
class CreateTableCollectionCourse extends Migration
{
    /**
     * Запуск миграции.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('collection_course', function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();
            $table->bigInteger('collection_id')->unsigned()->index('collection_id')->nullable();
            $table->bigInteger('course_id')->unsigned()->index('course_id')->nullable();
        });
    }

    /**
     * Запуск отката миграции.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::drop('collection_course');
    }
}
