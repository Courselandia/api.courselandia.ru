<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Tests\Feature\Http\Controllers\Site;

use App\Modules\Category\Models\Category;
use App\Modules\Course\Models\CourseFeature;
use App\Modules\Course\Models\CourseLearn;
use App\Modules\Course\Models\CourseLevel;
use App\Modules\Direction\Models\Direction;
use App\Modules\Employment\Models\Employment;
use App\Modules\Process\Models\Process;
use App\Modules\Profession\Models\Profession;
use App\Modules\Review\Models\Review;
use App\Modules\Skill\Models\Skill;
use App\Modules\Teacher\Models\Teacher;
use App\Modules\Tool\Models\Tool;
use App\Modules\Course\Models\Course;
use Tests\TestCase;

/**
 * Тестирование: Класс контроллер для курсов.
 */
class CourseControllerTest extends TestCase
{
    /**
     * Получение записи.
     *
     * @return void
     */
    public function testGet(): void
    {
        $course = self::createCourse();

        $this->json(
            'GET',
            'api/private/site/course/get/' . $course->school->link . '/' . $course->link,
        )->assertStatus(200)->assertJsonStructure([
            'data' => [
                'course' => $this->getCourseStructure(),
            ],
            'success',
        ]);
    }

    /**
     * Чтение записей.
     *
     * @return void
     */
    public function testRead(): void
    {
        $course = self::createCourse();
        self::createCourse();
        self::createCourse();

        $this->json(
            'GET',
            'api/private/site/course/read',
            [
                'offset' => 0,
                'limit' => 10,
                'sorts' => [
                    'name' => 'ASC',
                ],
                'filters' => [
                    'school-id' => $course->school_id,
                ],
                'section' => 'direction',
                'sectionLink' => $course->directions[0]->link,
            ],
        )->assertStatus(200)->assertJsonStructure([
            'data' => [
                'courses' => [
                    '*' => $this->getCoursesStructure()
                ],
                'description' => $this->getDescriptionStructure(),
                'filter' => [
                    'ratings',
                    'price' => [
                        'min',
                        'max',
                    ],
                    'duration' => [
                        'min',
                        'max',
                    ],
                    'credit',
                    'free',
                    'formats',
                    'levels',
                ],
                'section',
                'total'
            ],
            'success',
        ]);
    }

    /**
     * Чтение записей.
     *
     * @return void
     */
    public function testReadRated(): void
    {
        self::createCourse();
        self::createCourse();
        self::createCourse();

        $this->json(
            'GET',
            'api/private/site/course/read/rated',
            [
                'limit' => 10,
            ],
        )->assertStatus(200)->assertJsonStructure([
            'data' => [
                '*' => $this->getCoursesStructure(),
            ],
            'success',
        ]);
    }

    /**
     * Чтение записей.
     *
     * @return void
     */
    public function testReadSearch(): void
    {
        $course = self::createCourse();
        self::createCourse();
        self::createCourse();

        $this->json(
            'GET',
            'api/private/site/course/read/rated',
            [
                'limit' => 10,
                'search' => $course->name,
            ],
        )->assertStatus(200)->assertJsonStructure([
            'data' => [
                '*' => $this->getCoursesStructure(),
            ],
            'success',
        ]);
    }

    /**
     * Чтение записей.
     *
     * @return void
     */
    public function testReadFavorites(): void
    {
        $course = [
            self::createCourse(),
            self::createCourse(),
            self::createCourse(),
        ];

        $this->json(
            'GET',
            'api/private/site/course/read/favorites',
            [
                'ids' => [
                    $course[0]->id,
                    $course[1]->id,
                ],
            ],
        )->assertStatus(200)->assertJsonStructure([
            'data' => [
                '*' => $this->getCoursesStructure(),
            ],
            'success',
        ]);
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
            'api/private/site/course/get/school-link/course-link',
        )->assertStatus(404)->assertJsonStructure([
            'data',
            'success',
        ]);
    }

    /**
     * Чтение правлений.
     *
     * @return void
     */
    public function testDirections(): void
    {
        self::createCourse();
        self::createCourse();

        $this->json(
            'GET',
            'api/private/site/course/directions',
        )->assertStatus(200)->assertJsonStructure([
            'data' => [
                '*' => $this->getCourseFilterItem()
            ],
            'success',
        ]);
    }

    /**
     * Чтение категорий.
     *
     * @return void
     */
    public function testCategories(): void
    {
        self::createCourse();
        self::createCourse();

        $this->json(
            'GET',
            'api/private/site/course/categories',
        )->assertStatus(200)->assertJsonStructure([
            'data' => [
                '*' => $this->getCourseFilterItem()
            ],
            'success',
        ]);
    }

    /**
     * Чтение профессий.
     *
     * @return void
     */
    public function testProfessions(): void
    {
        self::createCourse();
        self::createCourse();

        $this->json(
            'GET',
            'api/private/site/course/professions',
        )->assertStatus(200)->assertJsonStructure([
            'data' => [
                '*' => $this->getCourseFilterItem()
            ],
            'success',
        ]);
    }

    /**
     * Чтение школ.
     *
     * @return void
     */
    public function testSchools(): void
    {
        self::createCourse();
        self::createCourse();

        $this->json(
            'GET',
            'api/private/site/course/schools',
        )->assertStatus(200)->assertJsonStructure([
            'data' => [
                '*' => $this->getCourseFilterItem()
            ],
            'success',
        ]);
    }

    /**
     * Чтение инструментов.
     *
     * @return void
     */
    public function testTools(): void
    {
        self::createCourse();
        self::createCourse();

        $this->json(
            'GET',
            'api/private/site/course/tools',
        )->assertStatus(200)->assertJsonStructure([
            'data' => [
                '*' => $this->getCourseFilterItem()
            ],
            'success',
        ]);
    }

    /**
     * Чтение навыков.
     *
     * @return void
     */
    public function testSkills(): void
    {
        self::createCourse();
        self::createCourse();

        $this->json(
            'GET',
            'api/private/site/course/skills',
        )->assertStatus(200)->assertJsonStructure([
            'data' => [
                '*' => $this->getCourseFilterItem()
            ],
            'success',
        ]);
    }

    /**
     * Чтение учителей.
     *
     * @return void
     */
    public function testTeachers(): void
    {
        self::createCourse();
        self::createCourse();

        $this->json(
            'GET',
            'api/private/site/course/teachers',
        )->assertStatus(200)->assertJsonStructure([
            'data' => [
                '*' => $this->getCourseFilterItem()
            ],
            'success',
        ]);
    }

    /**
     * Чтение статистики.
     *
     * @return void
     */
    public function testStat(): void
    {
        self::createCourse();
        self::createCourse();
        Review::factory()->count(2)->create();

        $this->json(
            'GET',
            'api/private/site/course/stat',
        )->assertStatus(200)->assertExactJson([
            'data' => [
                'amountCourses' => 2,
                'amountSchools' => 4,
                'amountTeachers' => 4,
                'amountReviews' => 2,
            ],
            'success' => true,
        ]);
    }

    /**
     * Создание курса.
     *
     * @return Course Вернет курс.
     */
    static public function createCourse(): Course
    {
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

        $course->direction_ids = collect($directions)->pluck('id');
        $course->profession_ids = collect($professions)->pluck('id');
        $course->category_ids = collect($categories)->pluck('id');
        $course->skill_ids = collect($skills)->pluck('id');
        $course->teacher_ids = collect($teachers)->pluck('id');
        $course->tool_ids = collect($tools)->pluck('id');
        $course->has_active_school = true;
        $course->level_values = [1, 2, 3];

        $course->save();

        return $course;
    }

    /**
     * Получить структуру данных пункта фильтров.
     *
     * @return array Массив структуры данных фильтра.
     */
    private function getCourseFilterItem(): array
    {
        return [
            'id',
            'name',
        ];
    }

    /**
     * Получить структуру данных курса.
     *
     * @return array Массив структуры данных курса.
     */
    private function getCourseStructure(): array
    {
        return [
            'id',
            'uuid',
            'metatag_id',
            'school_id',
            'image_big_id',
            'image_middle_id',
            'image_small_id',
            'name',
            'header',
            'text',
            'name_morphy',
            'text_morphy',
            'link',
            'url',
            'rating',
            'price',
            'price_old',
            'price_recurrent',
            'currency',
            'duration',
            'duration_rate',
            'duration_unit',
            'lessons_amount',
            'modules_amount',
            'status',
            'created_at',
            'updated_at',
            'deleted_at',
            'directions' => [
                '*' => [
                    'id',
                    'name',
                    'header',
                    'weight',
                    'link',
                    'text',
                    'status',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                ]
            ],
            'professions' => [
                '*' => [
                    'id',
                    'name',
                    'header',
                    'link',
                    'text',
                    'status',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                    'salaries' => [
                        '*' => [
                            "id",
                            "profession_id",
                            "level",
                            "salary",
                            "status",
                            "profession",
                            "created_at",
                            "updated_at",
                            "deleted_at",
                        ]
                    ]
                ]
            ],
            'metatag' => [
                'id',
                'description',
                'keywords',
                'title',
                'created_at',
                'updated_at',
                'deleted_at',
            ],
            'categories' => [
                '*' => [
                    'id',
                    'name',
                    'header',
                    'link',
                    'text',
                    'status',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                ]
            ],
            'skills' => [
                '*' => [
                    'id',
                    'name',
                    'header',
                    'link',
                    'text',
                    'status',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                ]
            ],
            'teachers' => [
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
            ],
            'tools' => [
                '*' => [
                    'id',
                    'name',
                    'header',
                    'link',
                    'text',
                    'status',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                ]
            ],
            'processes' => [
                '*' => [
                    'id',
                    'name',
                    'text',
                    'status',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                ]
            ],
            'levels' => [
                '*' => [
                    'id',
                    'course_id',
                    'level',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                ]
            ],
            'learns' => [
                '*' => [
                    'id',
                    'course_id',
                    'text',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                ]
            ],
            'employments' => [
                '*' => [
                    'id',
                    'name',
                    'text',
                ]
            ],
            'features' => [
                '*' => [
                    'id',
                    'course_id',
                    'text',
                ]
            ]
        ];
    }

    /**
     * Получить структуру данных курсов.
     *
     * @return array Массив структуры данных курса.
     */
    private function getCoursesStructure(): array
    {
        return [
            'id',
            'school_id',
            'name',
            'header',
            'link',
            'url',
            'rating',
            'price',
            'price_old',
            'price_recurrent',
            'currency',
            'duration',
            'duration_rate',
            'duration_unit',
            'lessons_amount',
            'modules_amount',
        ];
    }

    /**
     * Получить структуру описания раздела курсов.
     *
     * @return array Массив структуры описания.
     */
    private function getDescriptionStructure(): array
    {
        return [
            'id',
            'name',
            'link'
        ];
    }

    /**
     * Получить структуру фильтра.
     *
     * @return array Массив структуры фильтра.
     */
    private function getFilterStructure(): array
    {
        return [
            'id',
            'name',
            'link',
        ];
    }
}
