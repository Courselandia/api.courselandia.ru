<?php
/**
 * Анализатор текстов для SEO проверки.
 * Пакет содержит классы для хранения результатов анализа текстов для SEO.
 *
 * @package App.Models.Analyzer
 */

namespace App\Modules\Analyzer\Actions\Admin;

use Cache;
use Util;
use AnalyzerCategory;
use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Modules\Analyzer\Entities\Analyzer as AnalyzerEntity;
use App\Modules\Analyzer\Models\Analyzer;

/**
 * Класс действия для получения данных об анализе.
 */
class AnalyzerGetAction extends Action
{
    /**
     * ID анализатора.
     *
     * @var int|string
     */
    private int|string $id;

    /**
     * @param int|string $id ID анализатора.
     */
    public function __construct(int|string $id)
    {
        $this->id = $id;
    }

    /**
     * Метод запуска логики.
     *
     * @return AnalyzerEntity|null Вернет результаты исполнения.
     */
    public function run(): ?AnalyzerEntity
    {
        $cacheKey = Util::getKey('analyzer', $this->id);

        return Cache::tags(['analyzer'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $analyzer = Analyzer::where('id', $this->id)
                    ->with('analyzerable')
                    ->first();

                if ($analyzer) {
                    $analyzer = $analyzer->toArray();
                    $analyzerable = $analyzer['analyzerable'];
                    $analyzer['analyzerable'] = null;

                    $entity = AnalyzerEntity::from($analyzer);
                    $field = AnalyzerCategory::driver($entity->category)->field();
                    $entity->category_name = AnalyzerCategory::driver($entity->category)->name();
                    $entity->category_label = AnalyzerCategory::driver($entity->category)->label($analyzer['analyzerable_id']);
                    $entity->text = $analyzerable[$field];

                    return $entity;
                }

                return null;
            }
        );
    }
}
