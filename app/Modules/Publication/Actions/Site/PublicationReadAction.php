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
use App\Modules\Publication\Decorators\Site\PublicationReadDecorator;
use App\Modules\Publication\Data\Actions\Site\PublicationRead as PublicationReadDataAction;
use App\Modules\Publication\Data\Decorators\PublicationRead as PublicationReadDecoratorData;

/**
 * Класс действия для чтения публикаций.
 */
class PublicationReadAction extends Action
{
    /**
     * @var PublicationReadDataAction Данные для чтения публикаций.
     */
    private PublicationReadDataAction $data;

    /**
     * @param PublicationReadDataAction $data Данные для чтения публикаций.
     */
    public function __construct(PublicationReadDataAction $data)
    {
        $this->data = $data;
    }

    /**
     * Метод запуска логики.
     *
     * @return PublicationList|Publication|null Вернет результаты исполнения.
     */
    public function run(): PublicationList|Publication|null
    {
        $decorator = new PublicationReadDecorator(PublicationReadDecoratorData::from($this->data->toArray()));

        return $decorator->setActions([
            PublicationRead::class,
            PublicationTotal::class,
            PublicationYear::class,
        ])->run();
    }
}
