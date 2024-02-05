<?php
/**
 * Анализатор текстов для SEO проверки.
 * Пакет содержит классы для хранения результатов анализа текстов для SEO.
 *
 * @package App.Models.Analyzer
 */

namespace App\Modules\Analyzer\Jobs;

use Log;
use Plagiarism;
use Cache;
use AnalyzerCategory;
use Illuminate\Bus\Queueable;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\PaymentException;
use App\Modules\Analyzer\Actions\Admin\AnalyzerGetAction;
use App\Modules\Analyzer\Enums\Status;
use App\Modules\Analyzer\Models\Analyzer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Exceptions\LimitException;
use App\Modules\Plagiarism\Exceptions\TextShortException;

/**
 * Задание на проведения анализа текста.
 */
class AnalyzerAnalyzeTextJob implements ShouldQueue
{
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use Dispatchable;

    /**
     * ID данных анализа (модель Analyzer).
     *
     * @var int
     */
    public int $id;

    /**
     * Категория.
     *
     * @var string
     */
    public string $category;

    /**
     * Конструктор.
     *
     * @param int $id ID данных анализа (модель Analyzer).
     * @param string $category Категория.
     */
    public function __construct(int $id, string $category)
    {
        $this->id = $id;
        $this->category = $category;
    }

    /**
     * Выполнение задачи.
     *
     * @return void
     * @throws ParameterInvalidException
     */
    public function handle(): void
    {
        try {
            $action = new AnalyzerGetAction($this->id);
            $analyzerEntity = $action->run();

            if ($analyzerEntity && $analyzerEntity->status === Status::PENDING) {
                $text = AnalyzerCategory::driver($this->category)->text($analyzerEntity->analyzerable_id);

                if ($text) {
                    try {
                        $taskId = Plagiarism::request($text);

                        Analyzer::find($this->id)->update([
                            'task_id' => $taskId,
                            'status' => Status::PROCESSING->value,
                        ]);

                        Cache::tags(['analyzer'])->flush();

                        AnalyzerSaveResultJob::dispatch($this->id)
                            ->delay(now()->addMinutes(2));
                    } catch (TextShortException) {
                        Analyzer::find($this->id)->update([
                            'status' => Status::SKIPPED->value,
                            'unique' => null,
                            'water' => null,
                            'spam' => null,
                            'tries' => 0,
                        ]);
                    }
                } else {
                    Analyzer::find($this->id)->update([
                        'status' => Status::SKIPPED->value,
                        'unique' => null,
                        'water' => null,
                        'spam' => null,
                        'tries' => 0,
                    ]);
                }
            }
        } catch (PaymentException|LimitException $error) {
            Log::error($error->getMessage());
        }
    }
}
