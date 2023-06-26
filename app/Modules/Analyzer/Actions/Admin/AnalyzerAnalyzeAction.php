<?php
/**
 * Анализатор текстов для SEO проверки.
 * Пакет содержит классы для хранения результатов анализа текстов для SEO.
 *
 * @package App.Models.Analyzer
 */

namespace App\Modules\Analyzer\Actions\Admin;

use Cache;
use Plagiarism;
use AnalyzerCategory;
use App\Models\Action;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Analyzer\Enums\Status;
use App\Modules\Analyzer\Entities\Analyzer as AnalyzerEntity;
use App\Modules\Analyzer\Models\Analyzer;
use App\Modules\Analyzer\Jobs\AnalyzerSaveResultJob;

/**
 * Класс действия для проведения повторного анализа.
 */
class AnalyzerAnalyzeAction extends Action
{
    /**
     * ID статьи.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * Метод запуска логики.
     *
     * @return AnalyzerEntity Вернет результаты исполнения.
     * @throws RecordNotExistException
     * @throws ParameterInvalidException
     */
    public function run(): AnalyzerEntity
    {
        $action = app(AnalyzerGetAction::class);
        $action->id = $this->id;
        $analyzerEntity = $action->run();

        if ($analyzerEntity) {
            $text = AnalyzerCategory::driver($analyzerEntity->category)->text($analyzerEntity->analyzerable_id);

            if ($text) {
                $taskId = Plagiarism::request($text);
                $analyzerEntity->task_id = $taskId;
                $analyzerEntity->status = Status::PROCESSING;

                Analyzer::find($this->id)->update($analyzerEntity->toArray());

                AnalyzerSaveResultJob::dispatch($this->id)
                    ->delay(now()->addMinutes(2));
            } else {
                $analyzerEntity->status = Status::SKIPPED;
                Analyzer::find($this->id)->update($analyzerEntity->toArray());
            }

            Cache::tags(['analyzer'])->flush();

            $action = app(AnalyzerGetAction::class);
            $action->id = $this->id;

            return $action->run();
        }

        throw new RecordNotExistException(
            trans('analyzer::actions.admin.analyzerAnalyzeAction.notExistAnalyzer')
        );
    }
}
