<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Класс миграции.
 */
class CreateTableReviews extends Migration
{
    /**
     * Запуск миграции.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();
            $table->bigInteger('school_id')->unsigned()->index('school_id');

            $table->string('name', 191);
            $table->string('title', 191)->nullable();
            $table->text('text');
            $table->integer('rating')->unsigned();

            $table->string('status', 20)->default('disabled')->index();

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
        Schema::drop('reviews');
    }
}
