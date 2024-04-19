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
use App\Modules\Metatag\Template\TemplateException;
use App\Modules\Term\Entities\Term as TermEntity;
use App\Modules\Term\Data\TermCreate;
use App\Modules\Term\Models\Term;

/**
 * Класс действия для создания термина.
 */
class TermCreateAction extends Action
{
    /**
     * Данные для создания термина.
     *
     * @var TermCreate
     */
    private TermCreate $data;

    /**
     * @param TermCreate $data Данные для создания термина.
     */
    public function __construct(TermCreate $data)
    {
        $this->data = $data;
    }

    /**
     * Метод запуска логики.
     *
     * @return TermEntity Вернет результаты исполнения.
     * @throws TemplateException
     * @throws Throwable
     */
    public function run(): TermEntity
    {
        $termEntity = TermEntity::from($this->data->toArray());

        $term = Term::create($termEntity->toArray());
        Cache::tags(['term'])->flush();

        $action = new TermGetAction($term->id);

        return $action->run();
    }
}
