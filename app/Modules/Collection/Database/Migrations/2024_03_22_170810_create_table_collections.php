<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Класс миграции.
 */
class CreateTableCollections extends Migration
{
    /**
     * Запуск миграции.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('collections', function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();
            $table->bigInteger('metatag_id')->unsigned()->index('metatag_id')->nullable();
            $table->bigInteger('direction_id')->unsigned()->index('direction_id');

            $table->string('name', 191)->index('name');
            $table->string('link', 191)->index('link');
            $table->text('text')->nullable();
            $table->text('additional')->nullable();
            $table->integer('amount')->unsigned();
            $table->string('sort_field', 25)->default('name');
            $table->string('sort_direction', 4)->default('ASC');
            $table->boolean('copied')->default(false);

            $table->string('image_small_id')->nullable()->index();
            $table->string('image_middle_id')->nullable()->index();
            $table->string('image_big_id')->nullable()->index();

            $table->json('image_small')->nullable();
            $table->json('image_middle')->nullable();
            $table->json('image_big')->nullable();

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
        Schema::drop('collections');
    }
}
