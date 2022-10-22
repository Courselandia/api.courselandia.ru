<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Класс миграции.
 */
class CreateTableMetatags extends Migration
{
    /**
     * Запуск миграции.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('metatags', function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();
            $table->text('description')->nullable();
            $table->text('keywords')->nullable();
            $table->text('title')->nullable();

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
        Schema::drop('metatags');
    }
}
