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
use App\Modules\Course\DbFile\Store;

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
     * Отключать не активные.
     *
     * @var bool
     */
    public bool $disabled = false;

    /**
     * Признак школы открыты.
     *
     * @var bool
     */
    public bool $openedSchools = false;

    /**
     * Признак категории открыты.
     *
     * @var bool
     */
    public bool $openedCategories = false;

    /**
     * Признак профессии открыты.
     *
     * @var bool
     */
    public bool $openedProfessions = false;

    /**
     * Признак учителя открыты.
     *
     * @var bool
     */
    public bool $openedTeachers = false;

    /**
     * Признак навыки открыты.
     *
     * @var bool
     */
    public bool $openedSkills = false;

    /**
     * Признак инструменты открыты.
     *
     * @var bool
     */
    public bool $openedTools = false;

    /**
     * Возможность брать данные с файлового хранилища.
     *
     * @var bool
     */
    public bool $dbFile = true;

    /**
     * Метод запуска логики.
     *
     * @return CourseRead|null Вернет результаты исполнения.
     */
    public function run(): ?CourseRead
    {
        if (
            $this->dbFile
            && !$this->openedSchools
            && !$this->openedCategories
            && !$this->openedProfessions
            && !$this->openedTeachers
            && !$this->openedSkills
            && !$this->openedTools
        ) {
            $result = Store::read($this->offset, $this->limit, $this->sorts, $this->filters);

            if ($result) {
                return $result;
            }
        }

        $decorator = app(CourseReadDecorator::class);
        $decorator->sorts = $this->sorts;
        $decorator->filters = $this->filters;
        $decorator->offset = $this->offset;
        $decorator->limit = $this->limit;
        $decorator->section = $this->section;
        $decorator->sectionLink = $this->sectionLink;
        $decorator->disabled = $this->disabled;
        $decorator->openedSchools = $this->openedSchools;
        $decorator->openedCategories = $this->openedCategories;
        $decorator->openedProfessions = $this->openedProfessions;
        $decorator->openedTeachers = $this->openedTeachers;
        $decorator->openedSkills = $this->openedSkills;
        $decorator->openedTools = $this->openedTools;

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
