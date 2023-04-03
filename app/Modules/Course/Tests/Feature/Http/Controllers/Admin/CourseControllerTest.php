<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Tests\Feature\Http\Controllers\Admin;

use App\Modules\Employment\Models\Employment;
use App\Modules\Process\Models\Process;
use Util;
use App\Modules\Category\Models\Category;
use App\Modules\Course\Enums\Currency;
use App\Modules\Course\Enums\Duration;
use App\Modules\Course\Enums\Language;
use App\Modules\Course\Enums\Status;
use App\Modules\Course\Models\CourseFeature;
use App\Modules\Course\Models\CourseLearn;
use App\Modules\Course\Models\CourseLevel;
use App\Modules\Direction\Models\Direction;
use App\Modules\Profession\Models\Profession;
use App\Modules\Salary\Enums\Level;
use App\Modules\School\Models\School;
use App\Modules\Skill\Models\Skill;
use App\Modules\Teacher\Models\Teacher;
use App\Modules\Tool\Models\Tool;
use App\Models\Test\TokenTest;
use App\Modules\Course\Models\Course;
use Faker\Factory as Faker;
use Illuminate\Http\UploadedFile;
use JetBrains\PhpStorm\Pure;
use Tests\TestCase;

/**
 * Тестирование: Класс контроллер для курсов.
 */
class CourseControllerTest extends TestCase
{
    use TokenTest;

    /**
     * Чтение данных.
     *
     * @return void
     */
    public function testRead(): void
    {
        $course = $this->createCourse();

        $this->json(
            'GET',
            'api/private/admin/course/read',
            [
                'offset' => 0,
                'limit' => 10,
                'sorts' => [
                    'name' => 'ASC',
                ],
                'filters' => [
                    'link' => $course->link,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'data' => [
                '*' => $this->getCourseStructure()
            ],
            'total',
            'success',
        ]);
    }

    /**
     * Получение записи.
     *
     * @return void
     */
    public function testGet(): void
    {
        $this->json(
            'GET',
            'api/private/admin/course/get/' . $this->createCourse()->id,
            [],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'data' => $this->getCourseStructure(true),
            'success',
        ]);
    }

    /**
     * Создание курса.
     *
     * @return Course Вернет курс.
     */
    private function createCourse(): Course {
        $course = Course::factory()->create();
        $directions = Direction::factory()->count(3)->create();
        $professions = Profession::factory()->count(4)->create();
        $categories = Category::factory()->count(2)->create();
        $skills = Skill::factory()->count(2)->create();
        $teachers = Teacher::factory()->count(2)->create();
        $tools = Tool::factory()->count(2)->create();
        $processes = Process::factory()->count(2)->create();
        $employments = Employment::factory()->count(5)->create();

        $course->directions()->sync($directions);
        $course->professions()->sync($professions);
        $course->categories()->sync($categories);
        $course->skills()->sync($skills);
        $course->teachers()->sync($teachers);
        $course->tools()->sync($tools);
        $course->processes()->sync($processes);
        $course->employments()->sync($employments);

        CourseFeature::factory()->count(4)->for($course)->create();
        CourseLearn::factory()->count(3)->for($course)->create();
        CourseLevel::factory()->count(3)->for($course)->create();

        return $course;
    }

    /**
     * Получение записи с ошибкой при отсутствии записи.
     *
     * @return void
     */
    public function testGetNotExist(): void
    {
        $this->json(
            'GET',
            'api/private/admin/course/get/1000',
            [],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(404)->assertJsonStructure([
            'data',
            'success',
        ]);
    }

    /**
     * Создание данных: упрощенный вариант.
     *
     * @return void
     */
    public function testCreateSimple(): void
    {
        $faker = Faker::create();
        $school = School::factory()->create();

        $this->json(
            'POST',
            'api/private/admin/course/create',
            [
                'school_id' => $school->id,
                'name' => $faker->text(191),
                'header_template' => $faker->text(191),
                'link' => Util::latin($faker->text(191)),
                'url' => $faker->url(),
                'status' => Status::ACTIVE,
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => $this->getCourseStructure(),
        ]);
    }

    /**
     * Создание данных.
     *
     * @return void
     */
    public function testCreate(): void
    {
        $this->json(
            'POST',
            'api/private/admin/course/create',
            $this->getCourseData(),
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => $this->getCourseStructure(true, true),
        ]);
    }

    /**
     * Вернет данные для создания курса.
     *
     * @return array
     */
    private function getCourseData(): array
    {
        $faker = Faker::create();
        $school = School::factory()->create();

        return [
            'school_id' => $school->id,
            'image' => UploadedFile::fake()->image('course.jpg', 1500, 1500),
            'name' => $faker->text(191),
            'header_template' => $faker->text(191),
            'text' => $faker->text(1000),
            'link' => Util::latin($faker->text(191)),
            'url' => $faker->url(),
            'language' => Language::RU->value,
            'rating' => 4.4,
            'price' => 150000,
            'price_old' => 90000,
            'price_recurrent' => 6000,
            'currency' => Currency::RUB->value,
            'online' => true,
            'employment' => true,
            'duration' => 12,
            'duration_unit' => Duration::MONTH->value,
            'lessons_amount' => $faker->numberBetween(100, 300),
            'modules_amount' => $faker->numberBetween(5, 30),
            'title' => $faker->text(191),
            'description' => $faker->text(191),
            'keywords' => $faker->text(191),
            'status' => Status::ACTIVE,
            'directions' => [
                Direction::factory()->create()->id,
                Direction::factory()->create()->id,
            ],
            'professions' => [
                Profession::factory()->create()->id,
                Profession::factory()->create()->id,
            ],
            'categories' => [
                Category::factory()->create()->id,
                Category::factory()->create()->id,
            ],
            'skills' => [
                Skill::factory()->create()->id,
                Skill::factory()->create()->id,
            ],
            'teachers' => [
                Teacher::factory()->create()->id,
                Teacher::factory()->create()->id,
            ],
            'tools' => [
                Tool::factory()->create()->id,
                Tool::factory()->create()->id,
            ],
            'processes' => [
                Process::factory()->create()->id,
                Process::factory()->create()->id,
            ],
            'employments' => [
                Employment::factory()->create()->id,
                Employment::factory()->create()->id,
            ],
            'levels' => [
                Level::JUNIOR->value,
                Level::MIDDLE->value,
            ],
            'learns' => [
                $faker->text(191),
                $faker->text(191),
            ],
            'features' => [
                [
                    'icon' => 'delete',
                    'text' => $faker->text(191)
                ]
            ]
        ];
    }

    /**
     * Создание данных с ошибкой в данных.
     *
     * @return void
     */
    public function testCreateNotValid(): void
    {
        $faker = Faker::create();

        $this->json(
            'POST',
            'api/private/admin/course/create',
            [
                'name' => $faker->text(191),
                'header_template' => $faker->text(191),
                'link' => Util::latin($faker->text(191)),
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(400)->assertJsonStructure([
            'success',
            'message',
        ]);
    }

    /**
     * Обновление данных.
     *
     * @return void
     */
    public function testUpdate(): void
    {
        $course = $this->createCourse();

        $this->json(
            'PUT',
            'api/private/admin/course/update/' . $course->id,
            $this->getCourseData(),
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => $this->getCourseStructure(true, true),
        ]);
    }

    /**
     * Обновление данных с ошибкой.
     *
     * @return void
     */
    public function testUpdateNotValid(): void
    {
        $faker = Faker::create();
        $course = $this->createCourse();

        $this->json(
            'PUT',
            'api/private/admin/course/update/' . $course->id,
            [
                'name' => $faker->text(191),
                'header_template' => $faker->text(191),
                'link' => Util::latin($faker->text(191)),
                'status' => 'TEST',
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(400)->assertJsonStructure([
            'success',
            'message',
        ]);
    }

    /**
     * Обновление данных с ошибкой для несуществующей записи.
     *
     * @return void
     */
    public function testUpdateNotExist(): void
    {
        $this->json(
            'PUT',
            'api/private/admin/course/update/1000',
            $this->getCourseData(),
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(404)->assertJsonStructure([
            'success',
            'message',
        ]);
    }

    /**
     * Удаление данных.
     *
     * @return void
     */
    public function testDestroy(): void
    {
        $course = $this->createCourse();

        $this->json(
            'DELETE',
            'api/private/admin/course/destroy',
            [
                'ids' => [$course->id],
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
        ]);
    }

    /**
     * Получить структуру данных публикации.
     *
     * @param bool $full Все данные от всех отношений.
     * @param bool $image Добавить структуру данных изображения.
     *
     * @return array Массив структуры данных публикации.
     */
    #[Pure] private function getCourseStructure(bool $full = false, bool $image = false): array
    {
        $structure = [
            'id',
            'uuid',
            'metatag_id',
            'school_id',
            'image_big_id',
            'image_middle_id',
            'image_small_id',
            'name',
            'header',
            'header_template',
            'text',
            'name_morphy',
            'text_morphy',
            'link',
            'url',
            'language',
            'rating',
            'price',
            'price_old',
            'price_recurrent',
            'currency',
            'online',
            'employment',
            'duration',
            'duration_rate',
            'duration_unit',
            'lessons_amount',
            'modules_amount',
            'status',
            'created_at',
            'updated_at',
            'deleted_at'
        ];

        if ($image) {
            $structure['image_big_id'] = $this->getImageStructure();
            $structure['image_middle_id'] = $this->getImageStructure();
            $structure['image_small_id'] = $this->getImageStructure();
        }

        if ($full) {
            $structure['directions'] = [
                '*' => [
                    'id',
                    'name',
                    'header',
                    'header_template',
                    'weight',
                    'link',
                    'text',
                    'status',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                ]
            ];

            $structure['professions'] = [
                '*' => [
                    'id',
                    'name',
                    'header',
                    'header_template',
                    'link',
                    'text',
                    'status',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                ]
            ];

            $structure['metatag'] = [
                'id',
                'description',
                'keywords',
                'title',
                'created_at',
                'updated_at',
                'deleted_at',
            ];

            $structure['categories'] = [
                '*' => [
                    'id',
                    'name',
                    'header',
                    'header_template',
                    'link',
                    'text',
                    'status',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                ]
            ];

            $structure['skills'] = [
                '*' => [
                    'id',
                    'name',
                    'header',
                    'header_template',
                    'link',
                    'text',
                    'status',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                ]
            ];

            $structure['teachers'] = [
                '*' => [
                    'id',
                    'metatag_id',
                    'name',
                    'link',
                    'text',
                    'rating',
                    'status',
                    'image_small_id',
                    'image_middle_id',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                ]
            ];

            $structure['tools'] = [
                '*' => [
                    'id',
                    'name',
                    'header',
                    'header_template',
                    'link',
                    'text',
                    'status',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                ]
            ];

            $structure['processes'] = [
                '*' => [
                    'id',
                    'name',
                    'text',
                    'status',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                ]
            ];

            $structure['levels'] = [
                '*' => [
                    'id',
                    'course_id',
                    'level',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                ]
            ];

            $structure['learns'] = [
                '*' => [
                    'id',
                    'course_id',
                    'text',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                ]
            ];

            $structure['employments'] = [
                '*' => [
                    'id',
                    'name',
                    'text',
                ]
            ];

            $structure['features'] = [
                '*' => [
                    'id',
                    'course_id',
                    'text',
                ]
            ];
        }

        return $structure;
    }

    /**
     * Получить структуру данных изображения.
     *
     * @return array Массив структуры данных изображения.
     */
    private function getImageStructure(): array
    {
        return [
            'id',
            'byte',
            'folder',
            'format',
            'cache',
            'width',
            'height',
            'path',
            'pathCache',
            'pathSource'
        ];
    }
}
