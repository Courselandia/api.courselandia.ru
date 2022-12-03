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

            $table->string('name', 191);
            $table->string('link', 191)->index('link');
            $table->text('text')->nullable();
            $table->float('rating', 3)->default(0);

            $table->string('image_small_id')->nullable()->index();
            $table->string('image_middle_id')->nullable()->index();

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
        Schema::drop('teachers');
    }
}
