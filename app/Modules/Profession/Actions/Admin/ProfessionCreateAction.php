<?php
/**
 * Модуль Профессии.
 * Этот модуль содержит все классы для работы с профессиями.
 *
 * @package App\Modules\Profession
 */

namespace App\Modules\Profession\Actions\Admin;

use App\Modules\Analyzer\Actions\Admin\AnalyzerUpdateAction;
use App\Modules\Metatag\Data\MetatagSet;
use App\Modules\Profession\Data\ProfessionCreate;
use Typography;
use App\Models\Action;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Metatag\Template\Template;
use App\Modules\Metatag\Template\TemplateException;
use App\Modules\Profession\Entities\Profession as ProfessionEntity;
use App\Modules\Profession\Models\Profession;
use App\Modules\Metatag\Actions\MetatagSetAction;
use Cache;

/**
 * Класс действия для создания профессии.
 */
class ProfessionCreateAction extends Action
{
    /**
     * @var ProfessionCreate Данные для создания профессии.
     */
    private ProfessionCreate $data;

    /**
     * @param ProfessionCreate $data Данные для создания профессии.
     */
    public function __construct(ProfessionCreate $data)
    {
        $this->data = $data;
    }

    /**
     * Метод запуска логики.
     *
     * @return ProfessionEntity Вернет результаты исполнения.
     * @throws ParameterInvalidException
     * @throws TemplateException
     */
    public function run(): ProfessionEntity
    {
        $template = new Template();
        $templateValues = [
            'profession' => $this->data->name,
            'countProfessionCourses' => 0,
        ];

        $action = new MetatagSetAction(MetatagSet::from([
            'description' => $template->convert($this->data->description_template, $templateValues),
            'title' => $template->convert($this->data->title_template, $templateValues),
            'description_template' => $this->data->description_template,
            'title_template' => $this->data->title_template,
            'keywords' => $this->data->keywords,
        ]));

        $metatag = $action->run();

        $professionEntity = ProfessionEntity::from([
            ...$this->data->toArray(),
            'name' => Typography::process($this->data->name, true),
            'header' => Typography::process($template->convert($this->data->header_template, $templateValues), true),
            'text' => Typography::process($this->data->text),
            'additional' => Typography::process($this->data->additional),
            'metatag_id' => $metatag->id,
        ]);

        $profession = Profession::create($professionEntity->toArray());
        Cache::tags(['catalog', 'category', 'direction', 'salary', 'profession'])->flush();

        $action = new AnalyzerUpdateAction($profession->id, Profession::class, 'profession.text');
        $action->run();

        $action = new ProfessionGetAction($profession->id);

        return $action->run();
    }
}
