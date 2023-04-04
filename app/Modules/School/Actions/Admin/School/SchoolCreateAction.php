<?php
/**
 * Модуль Школ.
 * Этот модуль содержит все классы для работы со школами.
 *
 * @package App\Modules\School
 */

namespace App\Modules\School\Actions\Admin\School;

use App\Models\Action;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Image\Entities\Image;
use App\Modules\Metatag\Actions\MetatagSetAction;
use App\Modules\Metatag\Template\Template;
use App\Modules\Metatag\Template\TemplateException;
use App\Modules\School\Entities\School as SchoolEntity;
use App\Modules\School\Models\School;
use Cache;
use Illuminate\Http\UploadedFile;

/**
 * Класс действия для создания школы.
 */
class SchoolCreateAction extends Action
{
    /**
     * Название.
     *
     * @var string|null
     */
    public ?string $name = null;

    /**
     * Шаблон заголовка.
     *
     * @var string|null
     */
    public ?string $header_template = null;

    /**
     * Ссылка.
     *
     * @var string|null
     */
    public ?string $link = null;

    /**
     * Текст.
     *
     * @var string|null
     */
    public ?string $text = null;

    /**
     * Рейтинг.
     *
     * @var float|null
     */
    public ?float $rating = null;

    /**
     * Сыылка на сайт.
     *
     * @var string|null
     */
    public ?string $site = null;

    /**
     * Изображение логотипа.
     *
     * @var int|UploadedFile|Image|null
     */
    public int|UploadedFile|Image|null $image_logo_id = null;

    /**
     * Изображение сайта.
     *
     * @var int|UploadedFile|Image|null
     */
    public int|UploadedFile|Image|null $image_site_id = null;

    /**
     * Статус.
     *
     * @var bool|null
     */
    public ?bool $status = null;

    /**
     * Шаблон описания.
     *
     * @var string|null
     */
    public ?string $description_template = null;

    /**
     * Ключевые слова.
     *
     * @var string|null
     */
    public ?string $keywords = null;

    /**
     * Шаблон заголовка.
     *
     * @var string|null
     */
    public ?string $title_template = null;

    /**
     * Метод запуска логики.
     *
     * @return SchoolEntity Вернет результаты исполнения.
     * @throws ParameterInvalidException
     * @throws TemplateException
     */
    public function run(): SchoolEntity
    {
        $action = app(MetatagSetAction::class);
        $template = new Template();

        $templateValues = [
            'school' => $this->name,
            'countSchoolCourses' => 0,
        ];

        $action->description = $template->convert($this->description_template, $templateValues);
        $action->title = $template->convert($this->title_template, $templateValues);
        $action->description_template = $this->description_template;
        $action->title_template = $this->title_template;
        $action->keywords = $this->keywords;

        $metatag = $action->run();

        $schoolEntity = new SchoolEntity();
        $schoolEntity->name = $this->name;
        $schoolEntity->header = $template->convert($this->header_template, $templateValues);
        $schoolEntity->header_template = $this->header_template;
        $schoolEntity->link = $this->link;
        $schoolEntity->text = $this->text;
        $schoolEntity->site = $this->site;
        $schoolEntity->rating = $this->rating;
        $schoolEntity->image_logo_id = $this->image_logo_id;
        $schoolEntity->image_site_id = $this->image_site_id;
        $schoolEntity->status = $this->status;
        $schoolEntity->metatag_id = $metatag->id;

        $school = School::create($schoolEntity->toArray());
        Cache::tags(['catalog', 'school', 'teacher', 'review', 'faq'])->flush();

        $action = app(SchoolGetAction::class);
        $action->id = $school->id;

        return $action->run();
    }
}
