<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Класс миграции.
 */
class CreateTableCollectionFilters extends Migration
{
    /**
     * Запуск миграции.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('collection_filters', function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();
            $table->bigInteger('collection_id')->unsigned()->index('collection_id')->nullable();
            $table->string('name', 191);
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
        Schema::drop('collection_filters');
    }
}
