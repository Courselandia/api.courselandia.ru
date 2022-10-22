<?php
/**
 * Модуль Публикации.
 * Этот модуль содержит все классы для работы с публикациями.
 *
 * @package App\Modules\Publication
 */

namespace App\Modules\Publication\Decorators\Site;

use App\Models\Decorator;
use App\Modules\Publication\Entities\Publication;
use App\Modules\Publication\Entities\PublicationList;
use App\Modules\Publication\Entities\PublicationRead;
use Illuminate\Pipeline\Pipeline;

/**
 * Класс декоратор для чтения публикаций.
 */
class PublicationReadDecorator extends Decorator
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
     * Метод обработчик события после выполнения всех действий декоратора.
     *
     * @return PublicationList|Publication|null Вернет массив данных при выполнении действия.
     */
    public function run(): PublicationList|Publication|null
    {
        $publicationRead = new PublicationRead();
        $publicationRead->year = $this->year;
        $publicationRead->limit = $this->limit;
        $publicationRead->page = $this->page;
        $publicationRead->id = $this->id;
        $publicationRead->link = $this->link;

        return app(Pipeline::class)
            ->send($publicationRead)
            ->through($this->getActions())
            ->thenReturn();
    }
}
