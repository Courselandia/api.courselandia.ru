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
            $table->bigInteger('metatag_id')->unsigned()->index('metatag_id')->nullable();
            $table->bigInteger('school_id')->unsigned()->index('school_id');

            $table->string('image_big_id')->nullable()->index();
            $table->string('image_middle_id')->nullable()->index();
            $table->string('image_small_id')->nullable()->index();

            $table->text('name');
            $table->text('header')->nullable();
            $table->text('header_template')->nullable();
            $table->text('text')->nullable();
            $table->text('name_morphy');
            $table->text('text_morphy')->nullable();

            $table->text('link');
            $table->string('url', 191);
            $table->string('language', 20)->nullable();
            $table->float('rating', 3)->unsigned()->nullable();

            $table->float('price', 9)->unsigned()->nullable()->index('price');
            $table->float('price_old', 9)->unsigned()->nullable()->index('price_old');
            $table->float('price_recurrent', 9)->unsigned()->nullable()->index('price_recurrent');
            $table->string('currency', 10)->nullable();

            $table->boolean('online')->default(0)->index()->nullable();
            $table->boolean('employment')->default(0)->index()->nullable();

            $table->integer('duration')->unsigned()->index('duration')->nullable();
            $table->float('duration_rate', 5)->unsigned()->index('duration_rate')->nullable();
            $table->string('duration_unit', 20)->nullable();

            $table->integer('lessons_amount')->unsigned()->nullable();
            $table->integer('modules_amount')->unsigned()->nullable();

            $table->json('image_small')->nullable();
            $table->json('image_middle')->nullable();
            $table->json('image_big')->nullable();

            $table->string('status', 20)->index();

            $table->timestamps();
            $table->softDeletes()->index();
        });

        DB::statement('ALTER TABLE courses ADD FULLTEXT search(name_morphy, text_morphy)');
        DB::statement('ALTER TABLE courses ADD FULLTEXT search_name(name_morphy)');
        DB::statement('ALTER TABLE courses ADD FULLTEXT search_text(text_morphy)');
        DB::statement('CREATE INDEX link ON courses(link(500))');
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
