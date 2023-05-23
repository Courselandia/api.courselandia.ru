<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Export\Jobs;

use Illuminate\Bus\Queueable;
use App\Modules\Course\Entities\CourseRead;
use App\Modules\Course\Models\CourseMongoDb;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

/**
 * Задача для формирования.
 */
abstract class CourseItemJob implements ShouldQueue
{
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use Dispatchable;

    /**
     * ID фильтра.
     *
     * @var int|null
     */
    public ?int $uuid = null;

    /**
     * Категория фильтра.
     *
     * @var string
     */
    public string $category;

    /**
     * Ссылка на секцию.
     *
     * @var string|null
     */
    public ?string $link = null;

    /**
     * Конструктор.
     *
     * @param string $category Категория.
     * @param int|null $uuid ID фильтра.
     * @param string|null $link Ссылка фильтра.
     */
    public function __construct(string $category, ?int $uuid = null, ?string $link = null)
    {
        $this->category = $category;
        $this->uuid = $uuid;
        $this->link = $link;
    }

    /**
     * Сохранение данных в файл.
     *
     * @param CourseRead $item Данные.
     *
     * @return void
     */
    protected function save(CourseRead $item): void
    {
        CourseMongoDb::create([
            'uuid' => $this->uuid,
            'category' => $this->category,
            'link' => $this->link,
            'data' => serialize($item),
        ]);
    }
}
