<?php
/**
 * Модуль ядра системы.
 * Этот модуль содержит все классы для работы с ядром системы.
 *
 * @package App\Modules\Core
 */

namespace App\Modules\Core\Typography\Tasks;

use Typography;
use Illuminate\Database\Eloquent\Builder;
use App\Modules\Profession\Models\Profession;

/**
 * Типографирование профессий.
 */
class ProfessionTask extends Task
{
    /**
     * Количество запускаемых заданий.
     *
     * @return int Количество.
     */
    public function count(): int
    {
        return $this->getQuery()->count();
    }

    /**
     * Запуск типографирования текстов.
     *
     * @return void
     */
    public function run(): void
    {
        $professions = $this
            ->getQuery()
            ->clone()
            ->get();

        foreach ($professions as $profession) {
            $profession->name = Typography::process($profession->name, true);
            $profession->header = Typography::process($profession->header, true);
            $profession->text = Typography::process($profession->text);

            $profession->save();

            $this->fireEvent('finished', [$profession]);
        }
    }

    /**
     * Получить запрос на записи, которые нужно типографировать.
     *
     * @return Builder Построитель запроса.
     */
    private function getQuery(): Builder
    {
        return Profession::orderBy('id', 'ASC');
    }
}
