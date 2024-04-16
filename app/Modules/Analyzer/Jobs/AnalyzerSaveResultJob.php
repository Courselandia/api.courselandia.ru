<?php
/**
 * Анализатор текстов для SEO проверки.
 * Пакет содержит классы для хранения результатов анализа текстов для SEO.
 *
 * @package App.Models.Analyzer
 */

namespace App\Modules\Analyzer\Jobs;

use App\Models\Exceptions\ProcessingException;
use App\Modules\Analyzer\Actions\Admin\AnalyzerGetAction;
use App\Modules\Analyzer\Enums\Status;
use App\Modules\Analyzer\Models\Analyzer;
use App\Modules\Plagiarism\Values\Quality;
use Cache;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Log;
use Plagiarism;
use Throwable;

/**
 * Задание на получения результата анализа текста.
 */
class AnalyzerSaveResultJob implements ShouldQueue
{
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use Dispatchable;

    private const MAX_TRIES = 5;

    /**
     * ID данных анализа (модель Analyzer).
     *
     * @var int
     */
    private int $id;

    /**
     * Конструктор.
     *
     * @param int $id ID данных анализа (модель Analyzer).
     */
    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * Выполнение задачи.
     *
     * @return void
     */
    public function handle(): void
    {
        $action = new AnalyzerGetAction($this->id);
        $analyzerEntity = $action->run();

        if ($analyzerEntity && $analyzerEntity->status === Status::PROCESSING) {
            try {
                /**
                 * @var Quality $result
                 */
                $result = Plagiarism::result($analyzerEntity->task_id);

                Analyzer::find($this->id)->update([
                    'unique' => $result->getUnique(),
                    'water' => $result->getWater(),
                    'spam' => $result->getSpam(),
                    'status' => Status::READY->value,
                    'tries' => $analyzerEntity->tries + 1,
                ]);
            } catch (ProcessingException) {
                if ($analyzerEntity->tries < self::MAX_TRIES) {
                    AnalyzerSaveResultJob::dispatch($this->id)
                        ->delay(now()->addMinutes(2));

                    Analyzer::find($this->id)->update([
                        'tries' => $analyzerEntity->tries + 1,
                    ]);
                } else {
                    Log::error('Достигнуто максимальное количество попыток получения анализ текста. ID: ' . $this->id . '. Task ID: ' . $analyzerEntity->task_id);

                    Analyzer::find($this->id)->update([
                        'status' => Status::FAILED->value,
                    ]);
                }
            } catch (Throwable $error) {
                Log::error('Ошибка получения анализ текста: ' . $error->getMessage() . '. ID: ' . $this->id . '. Task ID: ' . $analyzerEntity->task_id);
                Analyzer::find($this->id)->update([
                    'status' => Status::FAILED->value,
                ]);
            }

            Cache::tags(['analyzer'])->flush();
        }
    }
}
