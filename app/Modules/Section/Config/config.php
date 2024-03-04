<?php

use App\Modules\Category\Models\Category;
use App\Modules\Direction\Models\Direction;
use App\Modules\Profession\Models\Profession;
use App\Modules\School\Models\School;
use App\Modules\Skill\Models\Skill;
use App\Modules\Teacher\Models\Teacher;
use App\Modules\Tool\Models\Tool;

return [
    'name' => 'Section',
    'items' => [
        'category' => Category::class,
        'direction' => Direction::class,
        'profession' => Profession::class,
        'school' => School::class,
        'skill' => Skill::class,
        'teacher' => Teacher::class,
        'tool' => Tool::class,
    ],
];
