<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Json\Jobs;

use App\Modules\Course\Enums\Status;
use App\Modules\Tool\Models\Tool;

/**
 * Задача для формирования всех инструментов.
 */
class ToolsItemJob extends JsonItemJob
{
    /**
     * Конструктор.
     *
     * @param string $path Ссылка на файл для сохранения.
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * Выполнение задачи.
     *
     * @return void
     */
    public function handle(): void
    {
        $data = Tool::select([
            'tools.id',
            'tools.link',
            'tools.name',
        ])
        ->whereHas('courses', function ($query) {
            $query->where('status', Status::ACTIVE->value)
                ->where('has_active_school', true);
        })
        ->where('status', true)
        ->orderBy('name')
        ->get()
        ->toArray();

        $this->save($data);
    }
}
