<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Класс миграции.
 */
class CreateTableSkills extends Migration
{
    /**
     * Запуск миграции.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('skills', function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();
            $table->bigInteger('metatag_id')->unsigned()->index('metatag_id');

            $table->string('name', 191);
            $table->string('header', 191)->nullable();
            $table->string('link', 191)->index('link');
            $table->text('text')->nullable();

            $table->boolean('status')->default(0)->index();

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
        Schema::drop('skills');
    }
}
