<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Класс миграции.
 */
class AddColumnNormalizeToTableCourses extends Migration
{
    /**
     * Запуск миграции.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->json('direction_ids')->nullable();
            $table->json('profession_ids')->nullable();
            $table->json('category_ids')->nullable();
            $table->json('skill_ids')->nullable();
            $table->json('teacher_ids')->nullable();
            $table->json('tool_ids')->nullable();
            $table->json('level_values')->nullable();
            $table->boolean('has_active_school')->nullable()->index();
        });

        DB::statement('CREATE INDEX courses_direction_ids_index ON courses ( (CAST(direction_ids AS UNSIGNED ARRAY)) );');
        DB::statement('CREATE INDEX courses_profession_ids_index ON courses ( (CAST(profession_ids AS UNSIGNED ARRAY)) );');
        DB::statement('CREATE INDEX courses_category_ids_index ON courses ( (CAST(category_ids AS UNSIGNED ARRAY)) );');
        DB::statement('CREATE INDEX courses_skill_ids_index ON courses ( (CAST(skill_ids AS UNSIGNED ARRAY)) );');
        DB::statement('CREATE INDEX courses_teacher_ids_index ON courses ( (CAST(teacher_ids AS UNSIGNED ARRAY)) );');
        DB::statement('CREATE INDEX courses_tool_ids_index ON courses ( (CAST(tool_ids AS UNSIGNED ARRAY)) );');
        DB::statement('CREATE INDEX courses_level_values_index ON courses ( (CAST(level_values AS UNSIGNED ARRAY)) );');
    }

    /**
     * Запуск отката миграции.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('direction_ids');
            $table->dropColumn('profession_ids');
            $table->dropColumn('category_ids');
            $table->dropColumn('skill_ids');
            $table->dropColumn('teacher_ids');
            $table->dropColumn('tool_ids');
            $table->dropColumn('level_values');
            $table->dropColumn('level_values');
            $table->dropColumn('has_active_school');
        });
    }
}
