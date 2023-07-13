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
use App\Modules\Course\Models\CourseFeature;

/**
 * Типографирование особеннойстей курса.
 */
class CourseFeatureTask extends Task
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
        $courseFeatures = $this
            ->getQuery()
            ->clone()
            ->get();

        foreach ($courseFeatures as $courseFeature) {
            $courseFeature->text = Typography::process($courseFeature->text, true);

            $courseFeature->save();

            $this->fireEvent('finished', [$courseFeature]);
        }
    }

    /**
     * Получить запрос на записи, которые нужно типографировать.
     *
     * @return Builder Построитель запроса.
     */
    private function getQuery(): Builder
    {
        return CourseFeature::orderBy('id', 'ASC');
    }
}
