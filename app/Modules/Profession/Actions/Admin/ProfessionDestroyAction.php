<?php
/**
 * Модуль Профессии.
 * Этот модуль содержит все классы для работы с профессиями.
 *
 * @package App\Modules\Profession
 */

namespace App\Modules\Profession\Actions\Admin;

use App\Models\Action;
use App\Modules\Profession\Models\Profession;
use Cache;

/**
 * Класс действия для удаления профессии.
 */
class ProfessionDestroyAction extends Action
{
    /**
     * Массив ID пользователей.
     *
     * @var int[]|string[]
     */
    public ?array $ids = null;

    /**
     * Метод запуска логики.
     *
     * @return bool Вернет результаты исполнения.
     */
    public function run(): bool
    {
        if ($this->ids) {
            Profession::destroy($this->ids);
            Cache::tags(['catalog', 'category', 'direction', 'salary', 'profession'])->flush();
        }

        return true;
    }
}
