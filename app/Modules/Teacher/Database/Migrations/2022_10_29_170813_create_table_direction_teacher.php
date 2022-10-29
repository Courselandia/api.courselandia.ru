<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Класс миграции.
 */
class CreateTableDirectionTeacher extends Migration
{
    /**
     * Запуск миграции.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('direction_teacher', function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();
            $table->bigInteger('direction_id')->unsigned()->index('direction_id');
            $table->bigInteger('teacher_id')->unsigned()->index('teacher_id');
        });
    }

    /**
     * Запуск отката миграции.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::drop('direction_teacher');
    }
}
