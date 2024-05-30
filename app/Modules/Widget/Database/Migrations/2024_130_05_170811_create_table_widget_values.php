<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Класс миграции.
 */
class CreateTableWidgetValues extends Migration
{
    /**
     * Запуск миграции.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('widget_values', function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();
            $table->bigInteger('widget_id')->unsigned()->index('widget_id');

            $table->string('name', 191)->index('name');
            $table->json('value');

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
        Schema::drop('widget_values');
    }
}
