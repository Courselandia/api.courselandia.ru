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
            $table->text('template_description')->nullable();
            $table->text('template_title')->nullable();
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
            $table->dropColumn('template_description');
            $table->dropColumn('template_title');
        });
    }
}
