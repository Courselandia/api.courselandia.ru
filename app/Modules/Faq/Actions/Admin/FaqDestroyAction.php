<?php
/**
 * Модуль FAQ's.
 * Этот модуль содержит все классы для работы с FAQ's.
 *
 * @package App\Modules\Faq
 */

namespace App\Modules\Faq\Actions\Admin;

use App\Models\Action;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Faq\Repositories\Faq;
use Cache;

/**
 * Класс действия для удаления FAQ.
 */
class FaqDestroyAction extends Action
{
    /**
     * Репозиторий FAQ.
     *
     * @var Faq
     */
    private Faq $faq;

    /**
     * Массив ID пользователей.
     *
     * @var int[]|string[]
     */
    public ?array $ids = null;

    /**
     * Конструктор.
     *
     * @param  Faq  $faq  Репозиторий FAQ.
     */
    public function __construct(Faq $faq)
    {
        $this->faq = $faq;
    }

    /**
     * Метод запуска логики.
     *
     * @return bool Вернет результаты исполнения.
     * @throws ParameterInvalidException
     */
    public function run(): bool
    {
        if ($this->ids) {
            $ids = $this->ids;

            for ($i = 0; $i < count($ids); $i++) {
                $this->faq->destroy($ids[$i]);
            }

            Cache::tags(['catalog', 'school', 'faq'])->flush();
        }

        return true;
    }
}
