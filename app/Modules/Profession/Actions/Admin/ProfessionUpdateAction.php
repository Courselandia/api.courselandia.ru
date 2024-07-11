<?php
/**
 * Модуль Профессии.
 * Этот модуль содержит все классы для работы с профессиями.
 *
 * @package App\Modules\Profession
 */

namespace App\Modules\Profession\Actions\Admin;

use DB;
use App\Modules\Analyzer\Actions\Admin\AnalyzerUpdateAction;
use App\Modules\Metatag\Data\MetatagSet;
use App\Modules\Profession\Data\ProfessionUpdate;
use Throwable;
use Typography;
use App\Models\Action;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Course\Enums\Status;
use App\Modules\Course\Models\Course;
use App\Modules\Metatag\Template\Template;
use App\Modules\Metatag\Template\TemplateException;
use App\Modules\Profession\Entities\Profession as ProfessionEntity;
use App\Modules\Profession\Models\Profession;
use App\Modules\Metatag\Actions\MetatagSetAction;
use Cache;

/**
 * Класс действия для обновления профессий.
 */
class ProfessionUpdateAction extends Action
{
    /**
     * @var ProfessionUpdate Данные для обновления профессии.
     */
    private ProfessionUpdate $data;

    /**
     * @param ProfessionUpdate $data Данные для обновления профессии.
     */
    public function __construct(ProfessionUpdate $data)
    {
        $this->data = $data;
    }

    /**
     * Метод запуска логики.
     *
     * @return ProfessionEntity Вернет результаты исполнения.
     * @throws RecordNotExistException
     * @throws TemplateException
     * @throws Throwable
     */
    public function run(): ProfessionEntity
    {
        $action = new ProfessionGetAction($this->data->id);
        $professionEntity = $action->run();

        if ($professionEntity) {
            DB::transaction(function () use ($professionEntity) {
                $countProfessionCourses = Course::where('courses.status', Status::ACTIVE->value)
                    ->whereHas('school', function ($query) {
                        $query->active()
                            ->withCourses();
                    })
                    ->whereHas('professions', function ($query) {
                        $query->where('professions.id', $this->data->id);
                    })
                    ->count();

                $templateValues = [
                    'profession' => $this->data->name,
                    'countProfessionCourses' => $countProfessionCourses,
                ];

                $template = new Template();

                $action = new MetatagSetAction(MetatagSet::from([
                    'description' => Typography::process($template->convert($this->data->description_template, $templateValues), true),
                    'title' => Typography::process($template->convert($this->data->title_template, $templateValues), true),
                    'description_template' => $this->data->description_template,
                    'title_template' => $this->data->title_template,
                    'keywords' => $this->data->keywords,
                    'id' => $professionEntity->metatag_id ?: null,
                ]));

                $professionEntity = ProfessionEntity::from([
                    ...$professionEntity->toArray(),
                    ...$this->data->toArray(),
                    'metatag_id' => $action->run()->id,
                    'name' => Typography::process($this->data->name, true),
                    'header' => Typography::process($template->convert($this->data->header_template, $templateValues), true),
                    'text' => Typography::process($this->data->text),
                    'additional' => Typography::process($this->data->additional),
                ]);

                Profession::find($this->data->id)->update($professionEntity->toArray());
                Cache::tags(['catalog', 'profession'])->flush();

                $action = new AnalyzerUpdateAction($professionEntity->id, Profession::class, 'profession.text');
                $action->run();
            });

            $action = new ProfessionGetAction($this->data->id);

            return $action->run();
        }

        throw new RecordNotExistException(
            trans('profession::actions.admin.professionUpdateAction.notExistProfession')
        );
    }
}
