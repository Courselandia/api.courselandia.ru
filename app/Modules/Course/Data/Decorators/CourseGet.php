<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Data\Decorators;

use App\Models\EntityNew;
use App\Modules\Course\Entities\Course as CourseEntity;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;

/**
 * Данные для декоратора получения курса.
 */
class CourseGet extends EntityNew
{
    /**
     * Курс.
     *
     * @var CourseEntity|null
     */
    public ?CourseEntity $course = null;

    /**
     * Похожие курсы.
     *
     * @var ?DataCollection
     */
    #[DataCollectionOf(CourseEntity::class)]
    public ?DataCollection $similarities = null;

    /**
     * Ссылка школы.
     *
     * @var string|null
     */
    public string|null $school = null;

    /**
     * Ссылка курса.
     *
     * @var string|null
     */
    public string|null $link = null;

    /**
     * ID курса.
     *
     * @var string|int|null
     */
    public string|int|null $id = null;

    /**
     * @param CourseEntity|null $course Курс.
     * @param DataCollection|null $similarities Похожие курсы.
     * @param string|null $school Ссылка школы.
     * @param string|null $link Ссылка курса.
     * @param string|null $id ID курса.
     */
    public function __construct(
        ?CourseEntity   $course = null,
        ?DataCollection $similarities = null,
        string|null     $school = null,
        string|null     $link = null,
        string|null     $id = null,
    )
    {
        $this->course = $course;
        $this->similarities = $similarities;
        $this->school = $school;
        $this->link = $link;
        $this->id = $id;
    }
}
