<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Класс миграции.
 */
class CreateTableArticles extends Migration
{
    /**
     * Запуск миграции.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();
            $table->bigInteger('task_id')->unsigned('task_id')->index();

            $table->text('category');
            $table->string('request');
            $table->text('text')->nullable();
            $table->json('params')->nullable();
            $table->smallInteger('tries')->default(0);

            $table->string('status', 50)->index();

            $table->bigInteger('articleable_id')->unsigned()->index()->nullable();
            $table->string('articleable_type')->index()->nullable();

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
        Schema::drop('articles');
    }
}
