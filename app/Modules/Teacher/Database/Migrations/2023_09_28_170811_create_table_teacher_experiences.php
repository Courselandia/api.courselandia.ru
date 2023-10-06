<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Класс миграции.
 */
class CreateTableTeacherExperiences extends Migration
{
    /**
     * Запуск миграции.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('teacher_experiences', function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();

            $table->bigInteger('teacher_id')->unsigned()->index('teacher_id');
            $table->string('place', 191);
            $table->string('position', 191);
            $table->date('started')->nullable();
            $table->date('finished')->nullable();
            $table->integer('weight')->unsigned()->default(0);

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
        Schema::drop('teacher_experiences');
    }
}
