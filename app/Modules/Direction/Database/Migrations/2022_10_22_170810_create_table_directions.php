<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Класс миграции.
 */
class CreateTableDirections extends Migration
{
    /**
     * Запуск миграции.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('directions', function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();
            $table->bigInteger('metatag_id')->unsigned()->index('metatag_id')->nullable();

            $table->string('name', 191);
            $table->string('header', 191)->nullable();
            $table->string('link', 191)->index('link');
            $table->text('text')->nullable();
            $table->integer('weight')->nullable()->unsigned()->default(0)->index();

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
        Schema::drop('directions');
    }
}
