<?php
/**
 * Модуль Метатэги.
 * Этот модуль содержит все классы для работы с метатегами.
 *
 * @package App\Modules\Metatag
 */

namespace App\Modules\Metatag\Actions;

use App\Models\Action;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordNotExistException;
use App\Models\Rep\RepositoryQueryBuilder;
use App\Modules\Metatag\Repositories\Metatag;
use App\Modules\Metatag\Entities\Metatag as MetatagEntity;
use ReflectionException;

/**
 * Класс для установки метатэгов.
 */
class MetatagSetAction extends Action
{
    /**
     * Репозиторий для метатэгов.
     *
     * @var Metatag
     */
    private Metatag $metatag;

    /**
     * ID записи.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * Описание.
     *
     * @var string|null
     */
    public ?string $description = null;

    /**
     * Ключевые слова.
     *
     * @var string|null
     */
    public ?string $keywords = null;

    /**
     * Заголовок.
     *
     * @var string|null
     */
    public ?string $title = null;

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
     * @throws RecordNotExistException|ParameterInvalidException|ReflectionException
     */
    public function run(): ?MetatagEntity
    {
        $metatagEntity = new MetatagEntity();
        $metatagEntity->description = $this->description;
        $metatagEntity->keywords = $this->keywords;
        $metatagEntity->title = $this->title;

        if ($this->id) {
            $metatag = $this->metatag->get(new RepositoryQueryBuilder($this->id));

            if ($metatag) {
                $metatagEntity->id = $this->id;
                $id = $this->metatag->update($this->id, $metatagEntity);

                return $this->metatag->get(new RepositoryQueryBuilder($id));
            }
        } else {
            $id = $this->metatag->create($metatagEntity);

            return $this->metatag->get(new RepositoryQueryBuilder($id));
        }

        return null;
    }
}
