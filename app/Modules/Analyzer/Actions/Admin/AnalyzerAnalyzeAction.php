<?php
/**
 * Анализатор текстов для SEO проверки.
 * Пакет содержит классы для хранения результатов анализа текстов для SEO.
 *
 * @package App.Models.Analyzer
 */

namespace App\Modules\Analyzer\Actions\Admin;

use Util;
use Cache;
use Plagiarism;
use AnalyzerCategory;
use App\Models\Action;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Analyzer\Enums\Status;
use App\Modules\Analyzer\Entities\Analyzer as AnalyzerEntity;
use App\Modules\Analyzer\Models\Analyzer;
use App\Modules\Analyzer\Jobs\AnalyzerSaveResultJob;
use App\Modules\Plagiarism\Exceptions\TextShortException;

/**
 * Класс действия для проведения повторного анализа.
 */
class AnalyzerAnalyzeAction extends Action
{
    /**
     * ID статьи.
     *
     * @var int|string
     */
    private int|string $id;

    /**
     * @param int|string $id ID статьи.
     */
    public function __construct(int|string $id)
    {
        $this->id = $id;
    }

    /**
     * Метод запуска логики.
     *
     * @return AnalyzerEntity Вернет результаты исполнения.
     * @throws RecordNotExistException
     */
    public function run(): AnalyzerEntity
    {
        $action = new AnalyzerGetAction($this->id);
        $analyzerEntity = $action->run();

        if ($analyzerEntity) {
            $text = AnalyzerCategory::driver($analyzerEntity->category)->text($analyzerEntity->analyzerable_id);

            if ($text) {
                try {
                    $taskId = Plagiarism::request($text);
                    $analyzerEntity->task_id = $taskId;
                    $analyzerEntity->status = Status::PROCESSING;

                    Analyzer::find($this->id)->update($analyzerEntity->toArray());
                    $cacheKey = Util::getKey('analyzer', $this->id);
                    Cache::tags(['analyzer'])->forget($cacheKey);

                    AnalyzerSaveResultJob::dispatch($this->id)
                        ->delay(now()->addMinutes(2));
                } catch (TextShortException $error) {
                    $analyzerEntity->status = Status::SKIPPED;
                    Analyzer::find($this->id)->update($analyzerEntity->toArray());

                    $cacheKey = Util::getKey('analyzer', $this->id);
                    Cache::tags(['analyzer'])->forget($cacheKey);
                }
            } else {
                $analyzerEntity->status = Status::SKIPPED;
                Analyzer::find($this->id)->update($analyzerEntity->toArray());

                $cacheKey = Util::getKey('analyzer', $this->id);
                Cache::tags(['analyzer'])->forget($cacheKey);
            }

            $action = new AnalyzerGetAction($this->id);

            return $action->run();
        }

        throw new RecordNotExistException(
            trans('analyzer::actions.admin.analyzerAnalyzeAction.notExistAnalyzer')
        );
    }
}
