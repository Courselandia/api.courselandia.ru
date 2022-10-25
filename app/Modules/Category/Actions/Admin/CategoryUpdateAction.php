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
use App\Modules\Image\Entities\Image;
use App\Modules\Metatag\Actions\MetatagSetAction;
use Cache;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use ReflectionException;

/**
 * Класс действия для обновления категорий.
 */
class CategoryUpdateAction extends Action
{
    /**
     * Репозиторий категорий.
     *
     * @var Category
     */
    private Category $category;

    /**
     * ID категории.
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
        $action = app(CategoryGetAction::class);
        $action->id = $this->id;
        $categoryEntity = $action->run();

        if ($categoryEntity) {
            $action = app(MetatagSetAction::class);
            $action->description = $this->description;
            $action->keywords = $this->keywords;
            $action->title = $this->title;
            $metatag = $action->run();

            $categoryEntity->id = $this->id;
            $categoryEntity->metatag_id = $metatag->id;
            $categoryEntity->name = $this->name;
            $categoryEntity->header = $this->header;
            $categoryEntity->link = $this->link;
            $categoryEntity->text = $this->text;
            $categoryEntity->status = $this->status;

            $this->category->update($this->id, $categoryEntity);
            $this->category->directionSync($this->id, $this->directions);
            $this->category->professionSync($this->id, $this->professions);
            Cache::tags(['catalog', 'category', 'direction', 'profession'])->flush();

            $action = app(CategoryGetAction::class);
            $action->id = $this->id;

            return $action->run();
        }

        throw new RecordNotExistException(
            trans('category::actions.admin.categoryUpdateAction.notExistCategory')
        );
    }
}
