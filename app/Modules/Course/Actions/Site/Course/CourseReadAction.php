<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Actions\Site\Course;

use App\Models\Action;
use App\Modules\Course\Entities\CourseRead;
use App\Modules\Course\Pipes\Site\Read\FilterCategoryPipe;
use App\Modules\Course\Pipes\Site\Read\FilterCreditPipe;
use App\Modules\Course\Pipes\Site\Read\FilterDirectionPipe;
use App\Modules\Course\Pipes\Site\Read\FilterDurationPipe;
use App\Modules\Course\Pipes\Site\Read\FilterFreePipe;
use App\Modules\Course\Pipes\Site\Read\FilterLevelPipe;
use App\Modules\Course\Pipes\Site\Read\FilterOnlinePipe;
use App\Modules\Course\Pipes\Site\Read\FilterPricePipe;
use App\Modules\Course\Pipes\Site\Read\FilterProfessionPipe;
use App\Modules\Course\Pipes\Site\Read\FilterRatingPipe;
use App\Modules\Course\Pipes\Site\Read\FilterSchoolPipe;
use App\Modules\Course\Pipes\Site\Read\FilterSkillPipe;
use App\Modules\Course\Pipes\Site\Read\FilterTeacherPipe;
use App\Modules\Course\Pipes\Site\Read\FilterToolPipe;
use App\Modules\Course\Pipes\Site\Read\ReadPipe;
use App\Modules\Course\Pipes\Site\Read\DescriptionPipe;
use App\Modules\Course\Pipes\Site\Read\DataPipe;
use App\Modules\Course\Decorators\Site\CourseReadDecorator;

/**
 * Класс действия для получения курсов.
 */
class CourseReadAction extends Action
{
    /**
     * Сортировка данных.
     *
     * @var array|null
     */
    public ?array $sorts = null;

    /**
     * Фильтрация данных.
     *
     * @var array|null
     */
    public ?array $filters = null;

    /**
     * Начать выборку.
     *
     * @var int|null
     */
    public ?int $offset = null;

    /**
     * Лимит выборки.
     *
     * @var int|null
     */
    public ?int $limit = null;

    /**
     * Раздел описания.
     *
     * @var string|null
     */
    public ?string $section = null;

    /**
     * Ссылка на раздел описания.
     *
     * @var string|null
     */
    public ?string $sectionLink = null;

    /**
     * Метод запуска логики.
     *
     * @return CourseRead|null Вернет результаты исполнения.
     */
    public function run(): ?CourseRead
    {
        $decorator = app(CourseReadDecorator::class);
        $decorator->sorts = $this->sorts;
        $decorator->filters = $this->filters;
        $decorator->offset = $this->offset;
        $decorator->limit = $this->limit;
        $decorator->section = $this->section;
        $decorator->sectionLink = $this->sectionLink;

        return $decorator->setActions([
            ReadPipe::class,
            DescriptionPipe::class,
            FilterDirectionPipe::class,
            FilterCategoryPipe::class,
            FilterProfessionPipe::class,
            FilterSchoolPipe::class,
            FilterToolPipe::class,
            FilterSkillPipe::class,
            FilterTeacherPipe::class,
            FilterRatingPipe::class,
            FilterPricePipe::class,
            FilterCreditPipe::class,
            FilterFreePipe::class,
            FilterDurationPipe::class,
            FilterOnlinePipe::class,
            FilterLevelPipe::class,
            DataPipe::class,
        ])->run();
    }
}
