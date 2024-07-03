<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Класс миграции.
 */
class CreateTablePublications extends Migration
{
    /**
     * Запуск миграции.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('publications', function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();
            $table->bigInteger('metatag_id')->unsigned()->index('metatag_id')->nullable();

            $table->datetime('published_at')->index('publishedAt');
            $table->string('header', 191);
            $table->string('link', 191)->index('link');
            $table->text('anons')->nullable();
            $table->mediumText('article')->nullable();

            $table->string('image_big_id')->nullable()->index();
            $table->string('image_middle_id')->nullable()->index();
            $table->string('image_small_id')->nullable()->index();

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
        Schema::drop('publications');
    }
}
