<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Класс миграции.
 */
class CreateTablePromotions extends Migration
{
    /**
     * Запуск миграции.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();
            $table->bigInteger('school_id')->unsigned()->index('school_id');

            $table->string('uuid', 191)->nullable()->index('uuid');
            $table->string('title', 191);
            $table->text('description')->nullable();
            $table->date('date_start')->nullable();
            $table->date('date_end')->nullable();
            $table->string('url', 191);
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
        Schema::drop('promotions');
    }
}
