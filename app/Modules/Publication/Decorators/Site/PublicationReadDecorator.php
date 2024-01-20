<?php
/**
 * Модуль Публикации.
 * Этот модуль содержит все классы для работы с публикациями.
 *
 * @package App\Modules\Publication
 */

namespace App\Modules\Publication\Decorators\Site;

use App\Models\Decorator;
use App\Modules\Publication\Data\Decorators\PublicationRead as PublicationReadData;
use App\Modules\Publication\Entities\Publication;
use App\Modules\Publication\Entities\PublicationList;
use App\Modules\Publication\Data\Decorators\PublicationRead;
use Illuminate\Pipeline\Pipeline;

/**
 * Класс декоратор для чтения публикаций.
 */
class PublicationReadDecorator extends Decorator
{
    /**
     * Данные для декоратора для чтения публикаций.
     *
     * @var PublicationRead
     */
    private PublicationRead $data;

    /**
     * @param PublicationRead $data Данные для декоратора для чтения публикаций.
     */
    public function __construct(PublicationRead $data)
    {
        $this->data = $data;
    }

    /**
     * Метод обработчик события после выполнения всех действий декоратора.
     *
     * @return PublicationList|Publication|null Вернет сущность результата исполнения.
     */
    public function run(): PublicationList|Publication|null
    {
        $data = app(Pipeline::class)
            ->send($this->data)
            ->through($this->getActions())
            ->thenReturn();

        if ($this->data->id || $this->data->link) {
            /**
             * @var PublicationReadData $data
             */
            return $data->publication ? Publication::from($data->publication->toArray()) : null;
        } else {
            return PublicationList::from([
                'publications' => $data->publications,
                'year' => $data->year,
                'years' => $data->years,
                'total' => $data->total,
            ]);
        }
    }
}
