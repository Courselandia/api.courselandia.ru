<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Tests\Feature\Http\Controllers\Site;

use App\Modules\Category\Models\Category;
use App\Modules\Course\Models\CourseEmployment;
use App\Modules\Course\Models\CourseFeature;
use App\Modules\Course\Models\CourseLearn;
use App\Modules\Course\Models\CourseLevel;
use App\Modules\Direction\Models\Direction;
use App\Modules\Profession\Models\Profession;
use App\Modules\Skill\Models\Skill;
use App\Modules\Teacher\Models\Teacher;
use App\Modules\Tool\Models\Tool;
use App\Modules\Course\Models\Course;
use JetBrains\PhpStorm\Pure;
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
        $this->json(
            'GET',
            'api/private/site/course/get/' . $this->createCourse()->id,
        )->assertStatus(200)->assertJsonStructure([
            'data' => $this->getCourseStructure(),
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
        $course = $this->createCourse();
        $this->createCourse();
        $this->createCourse();

        $this->json(
            'GET',
            'api/private/site/course/read',
            [
                'offset' => 0,
                'limit' => 10,
                'sorts' => [
                    'header' => 'ASC',
                ],
                'filters' => [
                    'school-id' => $course->school_id,
                ],
            ],
        )->assertStatus(200)->assertJsonStructure([
            'data' => [
                'courses' => [
                    '*' => $this->getCoursesStructure()
                ],
                'description' => $this->getDescriptionStructure(),
                'filter' => [
                    'directions' => [
                        '*' => $this->getFilterStructure()
                    ],
                    'categories' => [
                        '*' => $this->getFilterStructure()
                    ],
                    'professions' => [
                        '*' => $this->getFilterStructure()
                    ],
                    'schools' => [
                        '*' => $this->getFilterStructure()
                    ],
                    'tools' => [
                        '*' => $this->getFilterStructure()
                    ],
                    'skills' => [
                        '*' => $this->getFilterStructure()
                    ],
                    'teachers' => [
                        '*' => $this->getFilterStructure()
                    ],
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
                    'online',
                    'levels',
                ],
                'section',
                'total'
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
            'api/private/site/course/get/1000',
        )->assertStatus(404)->assertJsonStructure([
            'data',
            'success',
        ]);
    }

    public function testDirections(): void
    {
        $this->createCourse();
        $this->createCourse();

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

    public function testCategories(): void
    {
        $this->createCourse();
        $this->createCourse();

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

    public function testProfessions(): void
    {
        $this->createCourse();
        $this->createCourse();

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

    public function testSchools(): void
    {
        $this->createCourse();
        $this->createCourse();

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

    public function testTools(): void
    {
        $this->createCourse();
        $this->createCourse();

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

    public function testSkills(): void
    {
        $this->createCourse();
        $this->createCourse();

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

    public function testTeachers(): void
    {
        $this->createCourse();
        $this->createCourse();

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

        $course->directions()->sync($directions);
        $course->professions()->sync($professions);
        $course->categories()->sync($categories);
        $course->skills()->sync($skills);
        $course->teachers()->sync($teachers);
        $course->tools()->sync($tools);

        CourseEmployment::factory()->count(2)->for($course)->create();
        CourseFeature::factory()->count(4)->for($course)->create();
        CourseLearn::factory()->count(3)->for($course)->create();
        CourseLevel::factory()->count(3)->for($course)->create();

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
    #[Pure] private function getCourseStructure(): array
    {
        return [
            'id',
            'uuid',
            'metatag_id',
            'school_id',
            'image_big_id',
            'image_middle_id',
            'image_small_id',
            'header',
            'text',
            'header_morphy',
            'text_morphy',
            'link',
            'url',
            'language',
            'rating',
            'price',
            'price_discount',
            'price_recurrent_price',
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
                    'course_id',
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
    #[Pure] private function getCoursesStructure(): array
    {
        return [
            'id',
            'school_id',
            'image_big_id',
            'image_middle_id',
            'image_small_id',
            'header',
            'link',
            'url',
            'language',
            'rating',
            'price',
            'price_discount',
            'price_recurrent_price',
            'currency',
            'online',
            'employment',
            'duration',
            'duration_rate',
            'duration_unit',
            'lessons_amount',
            'modules_amount',
            'directions' => [
                '*' => [
                    'id',
                    'name',
                    'header',
                    'link',
                ]
            ],
            'professions' => [
                '*' => [
                    'id',
                    'name',
                    'header',
                    'link',
                ]
            ],
            'categories' => [
                '*' => [
                    'id',
                    'name',
                    'header',
                    'link',
                ]
            ],
            'skills' => [
                '*' => [
                    'id',
                    'name',
                    'header',
                    'link',
                ]
            ],
            'teachers' => [
                '*' => [
                    'id',
                    'name',
                    'link',
                    'rating',
                    'image_small_id',
                    'image_middle_id',
                ]
            ],
            'tools' => [
                '*' => [
                    'id',
                    'name',
                    'header',
                    'link',
                ]
            ],
            'levels' => [
                '*' => [
                    'id',
                    'course_id',
                    'level',
                ]
            ]
        ];
    }

    /**
     * Получить структуру описания раздела курсов.
     *
     * @return array Массив структуры описания.
     */
    #[Pure] private function getDescriptionStructure(): array
    {
        return [
            'id',
            'name',
            'header',
            'link'
        ];
    }

    /**
     * Получить структуру фильтра.
     *
     * @return array Массив структуры фильтра.
     */
    #[Pure] private function getFilterStructure(): array
    {
        return [
            'id',
            'name',
        ];
    }
}
