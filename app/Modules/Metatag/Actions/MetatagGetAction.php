<?php
/**
 * Модуль Метатэги.
 * Этот модуль содержит все классы для работы с метатегами.
 *
 * @package App\Modules\Metatag
 */

namespace App\Modules\Metatag\Actions;

use ReflectionException;
use App\Models\Action;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Rep\RepositoryQueryBuilder;
use App\Modules\Metatag\Entities\Metatag as MetatagEntity;
use App\Modules\Metatag\Repositories\Metatag;

/**
 * Класс для получения метатэгов.
 */
class MetatagGetAction extends Action
{
    /**
     * Репозиторий для метатэгов.
     *
     * @var Metatag
     */
    private Metatag $metatag;

    /**
     * ID метатэга.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * Конструктор.
     *
     * @param  Metatag  $metatag  Репозиторий метатэгов.
     */
    public function __construct(Metatag $metatag)
    {
        $this->metatag = $metatag;
    }

    /**
     * Метод запуска логики.
     *
     * @return MetatagEntity|null Вернет результаты исполнения.
     * @throws ParameterInvalidException|ReflectionException
     */
    public function run(): ?MetatagEntity
    {
        return $this->metatag->get(new RepositoryQueryBuilder($this->id));
    }
}
