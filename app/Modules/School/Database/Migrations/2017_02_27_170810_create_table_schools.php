<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Класс миграции.
 */
class CreateTableSchools extends Migration
{
    /**
     * Запуск миграции.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('schools', function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();
            $table->bigInteger('metatag_id')->unsigned()->index('metatag_id');

            $table->string('name', 191);
            $table->string('header', 191);
            $table->string('link', 191)->index('link');
            $table->text('text')->nullable();
            $table->string('site', 191)->nullable();
            $table->float('rating', 3)->default(0);

            $table->string('image_logo_id')->nullable()->index();
            $table->string('image_site_id')->nullable()->index();

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
        Schema::drop('schools');
    }
}
