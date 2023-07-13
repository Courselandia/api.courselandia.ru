<?php
/**
 * Модуль ядра системы.
 * Этот модуль содержит все классы для работы с ядром системы.
 *
 * @package App\Modules\Core
 */

namespace App\Modules\Core\Typography\Tasks;

use Typography;
use Illuminate\Database\Query\Builder;
use App\Modules\Publication\Models\Publication;

/**
 * Типографирование публикаций.
 */
class PublicationTask extends Task
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
        $publications = $this
            ->getQuery()
            ->clone()
            ->get();

        foreach ($publications as $publication) {
            $publication->header = Typography::process($publication->header, true);
            $publication->anons = Typography::process($publication->anons, true);
            $publication->article = Typography::process($publication->article);

            $publication->save();

            $this->fireEvent('finished', [$publication]);
        }
    }

    /**
     * Получить запрос на записи, которые нужно типографировать.
     *
     * @return Builder Построитель запроса.
     */
    private function getQuery(): Builder
    {
        return Publication::orderBy('id', 'ASC');
    }
}
