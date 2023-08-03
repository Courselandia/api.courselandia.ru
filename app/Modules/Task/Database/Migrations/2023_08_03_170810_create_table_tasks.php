<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Класс миграции.
 */
class CreateTableTasks extends Migration
{
    /**
     * Запуск миграции.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();
            $table->bigInteger('user_id')->unsigned()->index('user_id');

            $table->string('name', 191)->index('name');
            $table->text('reason')->nullable();
            $table->string('status', 30)->index('status');
            $table->dateTime('launched_at')->index('launched_at')->nullable();
            $table->dateTime('finished_at')->index('finished_at')->nullable();

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
        Schema::drop('tasks');
    }
}
