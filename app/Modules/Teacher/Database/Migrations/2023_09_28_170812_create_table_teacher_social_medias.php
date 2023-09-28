<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Класс миграции.
 */
class CreateTableTeacherSocialMedias extends Migration
{
    /**
     * Запуск миграции.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('teacher_social_medias', function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();

            $table->bigInteger('teacher_id')->unsigned()->index('teacher_id');
            $table->string('name', 191);
            $table->string('value', 191);

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
        Schema::drop('teacher_social_medias');
    }
}
