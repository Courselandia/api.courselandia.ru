<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Класс миграции.
 */
class CreateTableCourseFeatures extends Migration
{
    /**
     * Запуск миграции.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('course_features', function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();
            $table->bigInteger('course_id')->unsigned()->index('course_id');

            $table->string('icon', 30);
            $table->string('text', 191);

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
        Schema::drop('course_features');
    }
}
