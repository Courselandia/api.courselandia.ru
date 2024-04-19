<?php
/**
 * Модуль Термином.
 * Этот модуль содержит все классы для работы с терминами.
 *
 * @package App\Modules\Term
 */

namespace App\Modules\Term\Actions\Admin;

use Cache;
use Throwable;
use App\Models\Action;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Metatag\Template\TemplateException;
use App\Modules\Term\Entities\Term as TermEntity;
use App\Modules\Term\Models\Term;
use App\Modules\Term\Data\TermUpdate;

/**
 * Класс действия для обновления терминов.
 */
class TermUpdateAction extends Action
{
    /**
     * @var TermUpdate Данные для создания термина.
     */
    private TermUpdate $data;

    /**
     * @param TermUpdate $data Данные для создания термина.
     */
    public function __construct(TermUpdate $data)
    {
        $this->data = $data;
    }

    /**
     * Метод запуска логики.
     *
     * @return TermEntity Вернет результаты исполнения.
     * @throws RecordNotExistException
     * @throws TemplateException
     * @throws Throwable
     */
    public function run(): TermEntity
    {
        $action = new TermGetAction($this->data->id);
        $termEntity = $action->run();

        if ($termEntity) {
            $termEntity = TermEntity::from([
                ...$termEntity->toArray(),
                ...$this->data->toArray(),
            ]);

            Term::find($this->data->id)->update($termEntity->toArray());
            Cache::tags(['term'])->flush();

            $action = new TermGetAction($this->data->id);

            return $action->run();
        }

        throw new RecordNotExistException(
            trans('term::actions.admin.termUpdateAction.notExistTerm')
        );
    }
}
