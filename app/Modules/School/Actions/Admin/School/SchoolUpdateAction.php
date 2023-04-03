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
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Image\Entities\Image;
use App\Modules\Metatag\Actions\MetatagSetAction;
use App\Modules\Metatag\Template\Template;
use App\Modules\Metatag\Template\TemplateException;
use App\Modules\School\Entities\School as SchoolEntity;
use App\Modules\School\Models\School;
use Cache;
use Illuminate\Http\UploadedFile;

/**
 * Класс действия для обновления школ.
 */
class SchoolUpdateAction extends Action
{
    /**
     * ID школы.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

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
    public ?string $template_description = null;

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
    public ?string $template_title = null;

    /**
     * Метод запуска логики.
     *
     * @return SchoolEntity Вернет результаты исполнения.
     * @throws RecordNotExistException
     * @throws ParameterInvalidException
     * @throws TemplateException
     */
    public function run(): SchoolEntity
    {
        $action = app(SchoolGetAction::class);
        $action->id = $this->id;
        $schoolEntity = $action->run();

        if ($schoolEntity) {
            $templateValues = [];

            $template = new Template();

            $action = app(MetatagSetAction::class);
            $action->description = $template->convert($this->template_description, $templateValues);
            $action->title = $template->convert($this->template_title, $templateValues);
            $action->template_description = $this->template_description;
            $action->template_title = $this->template_title;
            $action->keywords = $this->keywords;
            $action->id = $schoolEntity->metatag_id ?: null;

            $schoolEntity->metatag_id = $action->run()->id;
            $schoolEntity->name = $this->name;
            $schoolEntity->header = $template->convert($this->header_template, $templateValues);
            $schoolEntity->link = $this->link;
            $schoolEntity->text = $this->text;
            $schoolEntity->site = $this->site;
            $schoolEntity->rating = $this->rating;
            $schoolEntity->status = $this->status;

            if ($this->image_logo_id) {
                $schoolEntity->image_logo_id = $this->image_logo_id;
            }

            if ($this->image_site_id) {
                $schoolEntity->image_site_id = $this->image_site_id;
            }

            School::find($this->id)->update($schoolEntity->toArray());
            Cache::tags(['catalog', 'school', 'teacher', 'review', 'faq'])->flush();

            $action = app(SchoolGetAction::class);
            $action->id = $this->id;
            return $action->run();
        }

        throw new RecordNotExistException(
            trans('school::actions.admin.schoolUpdateAction.notExistSchool')
        );
    }
}
