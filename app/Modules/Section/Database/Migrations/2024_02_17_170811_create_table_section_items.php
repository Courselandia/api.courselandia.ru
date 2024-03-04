<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Класс миграции.
 */
class CreateTableSectionItems extends Migration
{
    /**
     * Запуск миграции.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('section_items', function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();
            $table->bigInteger('section_id')->unsigned()->index('section_id');

            $table->integer('weight')->unsigned()->default(0)->index();

            $table->bigInteger('itemable_id')->unsigned()->index()->nullable();
            $table->string('itemable_type')->index()->nullable();

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
        Schema::drop('section_items');
    }
}
