<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Класс миграции.
 */
class CreateTableTeachers extends Migration
{
    /**
     * Запуск миграции.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();
            $table->bigInteger('metatag_id')->unsigned()->index('metatag_id')->nullable();

            $table->string('name', 191)->index('name');
            $table->string('link', 191)->index('link');
            $table->text('text')->nullable();
            $table->text('additional')->nullable();
            $table->float('rating', 3)->nullable();

            $table->string('image_small_id')->nullable()->index();
            $table->string('image_middle_id')->nullable()->index();

            $table->boolean('status')->default(1)->index();

            $table->json('image_small')->nullable();
            $table->json('image_middle')->nullable();
            $table->json('image_big')->nullable();

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
        Schema::drop('teachers');
    }
}
