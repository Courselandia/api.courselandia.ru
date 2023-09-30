<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Класс миграции.
 */
class UpdateTableTeachersAdditional extends Migration
{
    /**
     * Запуск миграции.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->string('city', 191)->nullable();
            $table->string('comment', 191)->nullable();
            $table->boolean('copied')->default(false);
            $table->string('image_big_id')->nullable()->index();
        });
    }

    /**
     * Запуск отката миграции.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->dropColumn('city');
            $table->dropColumn('comment');
            $table->dropColumn('copied');
            $table->dropColumn('image_big_id');
        });
    }
}
