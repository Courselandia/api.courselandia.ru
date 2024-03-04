<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Json\Sources;

use Config;
use Storage;
use App\Modules\Course\Json\Jobs\SectionItemLinkJob;
use App\Modules\Course\Json\Source;
use App\Modules\Section\Models\Section;
use Illuminate\Database\Eloquent\Builder;
use App\Modules\Salary\Enums\Level;

/**
 * Источник для формирования раздела.
 */
class SectionSource extends Source
{
    /**
     * Общее количество генерируемых данных.
     *
     * @return int Количество данных.
     */
    public function count(): int
    {
        return $this->getQuery()->count();
    }

    /**
     * Запуск экспорта данных.
     *
     * @return void.
     */
    public function export(): void
    {
        $count = $this->count();
        $configItems = Config::get('section.items');

        for ($i = 0; $i <= $count; $i++) {
            $section = $this->getQuery()
                ->limit(1)
                ->offset($i)
                ->first()
                ?->toArray();

            if ($section) {
                $fileName = $this->getFileName($section);

                if ($fileName) {
                    $items = [
                        [
                            'link' => $section['items'][0]['itemable']['link'],
                            'type' => array_search($section['items'][0]['itemable_type'], $configItems),
                        ]
                    ];

                    if (isset($section['items'][1])) {
                        $items[] = [
                            'link' => $section['items'][1]['itemable']['link'],
                            'type' => array_search($section['items'][1]['itemable_type'], $configItems),
                        ];
                    }

                    SectionItemLinkJob::dispatch(
                        '/json/sections/' . $fileName . '.json',
                        $section['id'],
                        $items,
                        $section['level'] ? Level::from($section['level']) : null,
                        $section['free'],
                    )->delay(now()->addMinute());

                    $this->fireEvent('export');
                }
            }
        }
    }

    /**
     * Запуск удаления не активных данных.
     *
     * @return void.
     */
    public function delete(): void
    {
        $activeIds = $this->getQuery()
            ->get()
            ->pluck('id');

        $sections = Section::whereNotIn('id', $activeIds)
            ->get()
            ?->toArray();

        $paths = [];

        foreach ($sections as $section) {
            $fileName = $this->getFileName($section);

            if ($fileName) {
                $path = '/json/sections/' . $fileName . '.json';

                if (Storage::drive('public')->exists($path)) {
                    $paths[] = $path;
                }
            }
        }

        Storage::drive('public')->delete($paths);
    }

    /**
     * Запрос для получения данных.
     *
     * @return Builder Запрос.
     */
    private function getQuery(): Builder
    {
        return Section::active()
            ->with([
                'items.itemable',
            ])
            ->orderBy('id');
    }

    /**
     * Получение название файла JSON, который будет хранить данные.
     *
     * @param array $section Массив данных раздела.
     * @return string|null Путь к файлу.
     */
    private function getFileName(array $section): ?string
    {
        $fileName = '';

        $items = Config::get('section.items');

        if (isset($section['items'][0]['itemable'])) {
            $sectionName = array_search($section['items'][0]['itemable_type'], $items);
            $fileName .= $sectionName . '_' . $section['items'][0]['itemable']['link'];

            if (isset($section['items'][1]['itemable'])) {
                $sectionName = array_search($section['items'][1]['itemable_type'], $items);
                $fileName .= '_' . $sectionName . '_' . $section['items'][1]['itemable']['link'];
            }

            if ($section['level']) {
                $fileName .= '_' . $section['level'];
            }

            if ($section['free']) {
                $fileName .= '_free';
            }

            return $fileName;
        }

        return null;
    }
}
