<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Класс миграции.
 */
class CreateTableCrawls extends Migration
{
    /**
     * Запуск миграции.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('crawls', function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();
            $table->bigInteger('page_id')->unsigned()->index('page_id');

            $table->string('task_id', 191)->nullable()->index('task_id');
            $table->datetime('pushed_at')->nullable()->index('pushed_at');
            $table->datetime('crawl_at')->nullable()->index('crawl_at');
            $table->string('engine', 50);


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
        Schema::drop('crawls');
    }
}
