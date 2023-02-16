<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Commands;

use App\Modules\Employment\Models\Employment;
use Config;
use Illuminate\Console\Command;
use App\Modules\Course\Models\Course;
use App\Modules\Course\Models\CourseFeature;
use App\Modules\Course\Models\CourseLearn;
use App\Modules\Course\Models\CourseLevel;
use App\Modules\Category\Models\Category;
use App\Modules\Direction\Models\Direction;
use App\Modules\Profession\Models\Profession;
use App\Modules\School\Models\School;
use App\Modules\Skill\Models\Skill;
use App\Modules\Teacher\Models\Teacher;
use App\Modules\Tool\Models\Tool;

/**
 * Заполнение тестовыми курсами базу данных.
 */
class CourseFillCommand extends Command
{
    /**
     * Название консольной команды.
     *
     * @var string
     */
    protected $signature = 'course:fill {--amount=2000 : Количество генерируемых курсов}';

    /**
     * Описание консольной команды.
     *
     * @var string
     */
    protected $description = 'Производит заполнение тестовыми курсами базу данных.';

    /**
     * Выполнение команды.
     *
     * @return void
     */
    public function handle(): void
    {
        if (Config::get('app.env') !== 'local') {
            $this->error('Запрещено запуск этой команды на продакшине.');

            return;
        }

        if ($this->confirm(
            'Вы точно хотите продолжить? Внимания, соглашаясь, вы удалите все текущие курсы в базе данных.'
        )) {
            $this->line('Удаление курсов в базе данных.');

            Course::truncate();
            CourseFeature::truncate();
            CourseLearn::truncate();
            CourseLevel::truncate();
            Category::truncate();
            Direction::truncate();
            Profession::truncate();
            School::truncate();
            Skill::truncate();
            Teacher::truncate();
            Tool::truncate();

            $this->line('Создание атрибутов направлений.');

            $amount = $this->option('amount');

            $directionAmount = 7;
            $directions = Direction::factory()->count($directionAmount)->create();

            $this->line('Создание атрибутов профессий.');

            $professionAmount = 500;
            $professions = Profession::factory()->count($professionAmount)->create();

            $this->line('Создание атрибутов категорий.');

            $categoryAmount = 30;
            $categories = Category::factory()->count($categoryAmount)->create();

            $this->line('Создание атрибутов навыков.');

            $skillAmount = 2000;
            $skills = Skill::factory()->count($skillAmount)->create();

            $this->line('Создание атрибутов учителей.');

            $teacherAmount = $amount * 3;
            $teachers = Teacher::factory()->count($teacherAmount)->create();

            $this->line('Создание атрибутов инструментов.');

            $toolAmount = 1000;
            $tools = Tool::factory()->count($toolAmount)->create();

            $employmentAmount = 1000;
            $tools = Employment::factory()->count($employmentAmount)->create();

            $this->line('Создание атрибутов школ.');

            $schoolAmount = 35;
            $schools = School::factory()->count($schoolAmount)->create();

            $this->line('Генерация курсов.');
            $bar = $this->output->createProgressBar($amount);
            $bar->start();

            for ($i = 0; $i < $amount; $i++) {
                $schoolIndex = rand(0, $schoolAmount - 1);

                $course = Course::factory()->create([
                    'school_id' => $schools[$schoolIndex]->id,
                ]);

                $directionStart = rand(0, $directionAmount);
                $course->directions()->sync($directions->slice($directionStart, rand(1, 2)));

                $professionStart = rand(0, $professionAmount);
                $course->professions()->sync($professions->slice($professionStart, rand(1, 3)));

                $categoryStart = rand(0, $categoryAmount);
                $course->categories()->sync($categories->slice($categoryStart, rand(1, 5)));

                $skillStart = rand(0, $skillAmount);
                $course->skills()->sync($skills->slice($skillStart, rand(1, 10)));

                $teacherStart = rand(0, $teacherAmount);
                $course->teachers()->sync($teachers->slice($teacherStart, rand(1, 10)));

                $toolStart = rand(0, $toolAmount);
                $course->tools()->sync($tools->slice($toolStart, rand(1, 10)));

                $employmentStart = rand(0, $employmentAmount);
                $course->employments()->sync($tools->slice($employmentStart, rand(1, 10)));

                CourseFeature::factory()->count(4)->for($course)->create();
                CourseLearn::factory()->count(3)->for($course)->create();
                CourseLevel::factory()->count(2)->for($course)->create();

                $bar->advance();
            }

            $bar->finish();

            $this->info("\n\nГенерация курсов окончена.");
        }
    }
}
