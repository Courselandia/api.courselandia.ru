<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Класс миграции.
 */
class AddColumnTemplateToTableMetatags extends Migration
{
    /**
     * Запуск миграции.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('metatags', function (Blueprint $table) {
            $table->text('description_template')->nullable();
            $table->text('title_template')->nullable();
        });
    }

    /**
     * Запуск отката миграции.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('metatags', function (Blueprint $table) {
            $table->dropColumn('description_template');
            $table->dropColumn('title_template');
        });
    }
}
