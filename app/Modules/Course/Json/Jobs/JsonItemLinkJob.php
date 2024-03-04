<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Json\Jobs;

use Storage;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

/**
 * Задача для формирования JSON данных.
 */
abstract class JsonItemLinkJob extends JsonItemJob
{
    /**
     * ID сущности.
     *
     * @var string|int|null
     */
    protected string|int|null $id;

    /**
     * Раздел.
     *
     * @var ?string
     */
    protected ?string $link;

    /**
     * Конструктор.
     *
     * @param string $path Ссылка на файл для сохранения.
     * @var string|int|null $id ID сущности.
     * @var ?string $link $id Раздел.
     */
    public function __construct(string $path, string|int|null $id = null, ?string $link = null)
    {
        $this->path = $path;
        $this->id = $id;
        $this->link = $link;
    }
}
