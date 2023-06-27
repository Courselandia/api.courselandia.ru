<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Класс миграции.
 */
class CreateTableAnalyzers extends Migration
{
    /**
     * Запуск миграции.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('analyzers', function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();
            $table->string('task_id', 30)->index()->nullable();

            $table->text('category');
            $table->float('unique')->default(0)->nullable();
            $table->smallInteger('water')->default(0)->nullable();
            $table->smallInteger('spam')->default(0)->nullable();

            $table->json('params')->nullable();
            $table->smallInteger('tries')->default(0);

            $table->string('status', 50)->index();

            $table->bigInteger('analyzerable_id')->unsigned()->index()->nullable();
            $table->string('analyzerable_type')->index()->nullable();

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
        Schema::drop('analyzers');
    }
}
