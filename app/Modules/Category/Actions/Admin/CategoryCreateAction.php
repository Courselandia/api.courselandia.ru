<?php
/**
 * Модуль Категорий.
 * Этот модуль содержит все классы для работы с категориями.
 *
 * @package App\Modules\Category
 */

namespace App\Modules\Category\Actions\Admin;

use App\Models\Action;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Category\Entities\Category as CategoryEntity;
use App\Modules\Category\Repositories\Category;
use App\Modules\Direction\Entities\Direction;
use App\Modules\Metatag\Actions\MetatagSetAction;
use App\Modules\Profession\Entities\Profession;
use Cache;
use ReflectionException;

/**
 * Класс действия для создания категории.
 */
class CategoryCreateAction extends Action
{
    /**
     * Репозиторий категорий.
     *
     * @var Category
     */
    private Category $category;

    /**
     * Название.
     *
     * @var string|null
     */
    public ?string $name = null;

    /**
     * Заголовок.
     *
     * @var string|null
     */
    public ?string $header = null;

    /**
     * Ссылка.
     *
     * @var string|null
     */
    public ?string $link = null;

    /**
     * Статья.
     *
     * @var string|null
     */
    public ?string $text = null;

    /**
     * Статус.
     *
     * @var bool|null
     */
    public ?bool $status = null;

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
     * ID направлений.
     *
     * @var int[]
     */
    public ?array $directions = null;

    /**
     * ID профессий.
     *
     * @var int[]
     */
    public ?array $professions = null;

    /**
     * Конструктор.
     *
     * @param  Category  $category  Репозиторий категорий.
     */
    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    /**
     * Метод запуска логики.
     *
     * @return CategoryEntity Вернет результаты исполнения.
     * @throws RecordNotExistException
     * @throws ParameterInvalidException
     * @throws ReflectionException
     */
    public function run(): CategoryEntity
    {
        $action = app(MetatagSetAction::class);
        $action->description = $this->description;
        $action->keywords = $this->keywords;
        $action->title = $this->title;
        $metatag = $action->run();

        $categoryEntity = new CategoryEntity();
        $categoryEntity->name = $this->name;
        $categoryEntity->header = $this->header;
        $categoryEntity->link = $this->link;
        $categoryEntity->text = $this->text;
        $categoryEntity->status = $this->status;
        $categoryEntity->metatag_id = $metatag->id;

        $id = $this->category->create($categoryEntity);
        $this->category->directionSync($id, $this->directions);
        $this->category->professionSync($id, $this->professions);

        Cache::tags(['catalog', 'category', 'direction', 'profession'])->flush();

        $action = app(CategoryGetAction::class);
        $action->id = $id;

        return $action->run();
    }
}
