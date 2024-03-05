<?php
/**
 * Модуль FAQ's.
 * Этот модуль содержит все классы для работы с FAQ's.
 *
 * @package App\Modules\Faq
 */

namespace App\Modules\Faq\Actions\Admin;

use App\Models\Action;
use App\Modules\Faq\Models\Faq;
use Cache;

/**
 * Класс действия для удаления FAQ.
 */
class FaqDestroyAction extends Action
{
    /**
     * Массив ID вопрос-ответов.
     *
     * @var int[]|string[]
     */
    private array $ids;

    /**
     * @param int[]|string[] $ids Массив ID вопрос-ответов.
     */
    public function __construct(array $ids)
    {
        $this->ids = $ids;
    }

    /**
     * Метод запуска логики.
     *
     * @return bool Вернет результаты исполнения.
     */
    public function run(): bool
    {
        if ($this->ids) {
            Faq::destroy($this->ids);

            Cache::tags(['catalog', 'faq'])->flush();
        }

        return true;
    }
}
