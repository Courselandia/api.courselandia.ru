<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Класс миграции.
 */
class CreateTableUserVerifications extends Migration
{
    /**
     * Запуск миграции.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('user_verifications', function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();
            $table->bigInteger('user_id')->unsigned()->index();
            $table->text('code');
            $table->boolean('status')->default(true)->index();

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
        Schema::drop('user_verifications');
    }
}
