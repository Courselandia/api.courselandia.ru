<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Класс миграции.
 */
class CreateTableFeedbacks extends Migration
{
    /**
     * Запуск миграции.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();
            $table->string('name', 191);
            $table->string('email', 191);
            $table->string('phone', 191)->nullable();
            $table->text('message')->nullable();

            $table->timestamps();
            $table->softDeletes()->index();
        });
    }

    /**
     * Запуск отката миграции.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('feedbacks');
    }
}
