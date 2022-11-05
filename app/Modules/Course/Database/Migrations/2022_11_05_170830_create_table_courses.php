<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Класс миграции.
 */
class CreateTableCourses extends Migration
{
    /**
     * Запуск миграции.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();
            $table->string('uuid', 191)->nullable()->index('uuid');
            $table->bigInteger('metatag_id')->unsigned()->index('metatag_id');
            $table->bigInteger('school_id')->unsigned()->index('school_id');

            $table->string('image_big_id')->nullable()->index();
            $table->string('image_middle_id')->nullable()->index();
            $table->string('image_small_id')->nullable()->index();

            $table->string('header', 191);
            $table->text('description')->nullable();
            $table->string('header_morphy', 191);
            $table->text('description_morphy')->nullable();

            $table->string('link', 191)->index('link');
            $table->string('url', 191);
            $table->string('language', 20)->nullable();
            $table->float('rating', 3)->unsigned()->default(1);

            $table->float('price')->unsigned()->nullable()->index('price');
            $table->float('price_discount')->unsigned()->nullable()->index('price_discount');
            $table->float('price_recurrent_price')->unsigned()->nullable()->index('price_recurrent_price');
            $table->string('currency', 10)->nullable();

            $table->boolean('online')->default(0)->index();
            $table->boolean('employment')->default(0)->index();

            $table->integer('duration')->unsigned()->index('duration')->nullable();
            $table->float('duration_rate', 5, 2)->unsigned()->index('duration_rate')->nullable();
            $table->string('duration_unit', 20)->nullable();

            $table->integer('lessons_amount')->unsigned()->nullable();
            $table->integer('modules_amount')->unsigned()->nullable();

            $table->string('status', 20)->index();

            $table->timestamps();
            $table->softDeletes()->index();
        });

        DB::statement('ALTER TABLE courses ADD FULLTEXT search(header_morphy, description_morphy)');
    }

    /**
     * Запуск отката миграции.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::drop('courses');
    }
}
