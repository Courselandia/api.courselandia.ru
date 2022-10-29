<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Класс миграции.
 */
class CreateTableSchoolTeacher extends Migration
{
    /**
     * Запуск миграции.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('school_teacher', function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();
            $table->bigInteger('school_id')->unsigned()->index('school_id');
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
        Schema::drop('school_teacher');
    }
}
