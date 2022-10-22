<?php
/**
 * Модуль Инфоблоков.
 * Этот модуль содержит все классы для работы с инфоблоками.
 *
 * @package App\Modules\Publication
 */

namespace App\Modules\Publication\Actions\Site;

use App\Models\Action;
use App\Modules\Publication\Entities\Publication;
use App\Modules\Publication\Entities\PublicationList;
use App\Modules\Publication\Pipes\Site\Read\PublicationTotal;
use App\Modules\Publication\Pipes\Site\Read\PublicationRead;
use App\Modules\Publication\Pipes\Site\Read\PublicationYear;
use App\Modules\Publication\Pipes\Site\Read\PublicationData;
use App\Modules\Publication\Decorators\Site\PublicationReadDecorator;

/**
 * Класс действия для чтения публикаций.
 */
class PublicationReadAction extends Action
{
    /**
     * Год.
     *
     * @var int|null
     */
    public ?int $year = null;

    /**
     * Лимит.
     *
     * @var int|null
     */
    public ?int $limit = null;

    /**
     * Страница.
     *
     * @var int|null
     */
    public ?int $page = null;

    /**
     * ID публикации.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * Ссылка.
     *
     * @var string|null
     */
    public ?string $link = null;

    /**
     * Метод запуска логики.
     *
     * @return PublicationList|Publication|null Вернет результаты исполнения.
     */
    public function run(): PublicationList|Publication|null
    {
        $decorator = app(PublicationReadDecorator::class);
        $decorator->year = $this->year;
        $decorator->limit = $this->limit;
        $decorator->page = $this->page;
        $decorator->id = $this->id;
        $decorator->link = $this->link;

        return $decorator->setActions([
            PublicationRead::class,
            PublicationTotal::class,
            PublicationYear::class,
            PublicationData::class
        ])->run();
    }
}
