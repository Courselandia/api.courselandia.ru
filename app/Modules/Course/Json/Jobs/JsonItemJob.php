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
abstract class JsonItemJob implements ShouldQueue
{
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use Dispatchable;

    /**
     * Ссылка на файл для сохранения.
     *
     * @var string
     */
    protected string $path;

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

    /**
     * Сохранение данных в файл.
     *
     * @param array $data Данные.
     *
     * @return void
     */
    protected function save(array $data): void
    {
        Storage::drive('public')->put($this->path, json_encode($data));
    }
}
