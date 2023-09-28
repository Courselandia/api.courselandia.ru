<?php
/**
 * Модуль Учителей.
 * Этот модуль содержит все классы для работы с учителями.
 *
 * @package App\Modules\Teacher
 */

namespace App\Modules\Teacher\Database\Factories;

use App\Modules\Teacher\Enums\SocialMedia;
use JetBrains\PhpStorm\ArrayShape;
use App\Modules\Teacher\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Modules\Teacher\Models\TeacherExperience;

/**
 * Фабрика модели социальных сетей учителя.
 */
class TeacherSocialMediaFactory extends Factory
{
    /**
     * Модель фабрики.
     *
     * @var string
     */
    protected $model = TeacherExperience::class;

    /**
     * Определение модели.
     *
     * @return array
     */
    #[ArrayShape([
        'teacher_id' => '\Illuminate\Database\Eloquent\Factories\Factory',
        'name' => 'string',
        'value' => 'string',
    ])] public function definition(): array
    {
        return [
            'teacher_id' => Teacher::factory(),
            'name' => SocialMedia::TELEGRAM,
            'value' => $this->faker->text(160),
        ];
    }
}
