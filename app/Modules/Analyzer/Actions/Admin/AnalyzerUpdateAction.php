<?php
/**
 * Анализатор текстов для SEO проверки.
 * Пакет содержит классы для хранения результатов анализа текстов для SEO.
 *
 * @package App.Models.Analyzer
 */

namespace App\Modules\Analyzer\Actions\Admin;

use Auth;
use Cache;
use Log;
use Plagiarism;
use AnalyzerCategory;
use App\Models\Action;
use App\Modules\Task\Jobs\Launcher;
use App\Models\Exceptions\LimitException;
use App\Models\Exceptions\PaymentException;
use App\Modules\Analyzer\Enums\Status;
use App\Modules\Analyzer\Jobs\AnalyzerSaveResultJob;
use App\Modules\Analyzer\Models\Analyzer;
use App\Modules\Course\Models\Course;
use App\Modules\Plagiarism\Exceptions\TextShortException;

/**
 * Класс действия для запуска проведения анализа текста
 * (проверка уникальности текста, его заспамленность и процент воды).
 */
class AnalyzerUpdateAction extends Action
{
    /**
     * ID анализируемой модели.
     *
     * @var int
     */
    private int $id;

    /**
     * Название класса анализируемой модели.
     *
     * @var string
     */
    private string $model;

    /**
     * Категория.
     *
     * @var string
     */
    private string $category;

    /**
     * @param int $id ID анализируемой модели.
     * @param string $model Название класса анализируемой модели.
     * @param string $category Категория.
     */
    public function __construct(int $id, string $model, string $category)
    {
        $this->id = $id;
        $this->model = $model;
        $this->category = $category;
    }

    /**
     * Выполнение задачи.
     *
     * @return boolean
     */
    public function run(): bool
    {
        try {
            /**
             * @var Course $model
             */
            $model = $this->model;
            $model = $model::where('id', $this->id)
                ->with('analyzers')
                ->first();

            if ($model) {
                $found = false;
                $analyzerModel = null;

                foreach ($model->analyzers as $analyzer) {
                    if ($analyzer->category === $this->category) {
                        $text = AnalyzerCategory::driver($this->category)->text($model->id);

                        if ($text) {
                            try {
                                $taskId = Plagiarism::request($text);

                                $analyzerModel = Analyzer::find($analyzer->id);

                                if ($analyzerModel) {
                                    $analyzerModel->update([
                                        'task_id' => $taskId,
                                        'status' => Status::PROCESSING->value,
                                        'unique' => null,
                                        'water' => null,
                                        'spam' => null,
                                        'tries' => 0,
                                    ]);

                                    $found = true;
                                }
                            } catch (TextShortException $error) {
                                $analyzerModel = Analyzer::find($analyzer->id);

                                if ($analyzerModel) {
                                    $found = true;

                                    $analyzerModel->update([
                                        'status' => Status::SKIPPED->value,
                                        'unique' => null,
                                        'water' => null,
                                        'spam' => null,
                                        'tries' => 0,
                                    ]);
                                } else {
                                    Analyzer::create([
                                        'status' => Status::SKIPPED->value,
                                        'category' => $this->category,
                                        'unique' => null,
                                        'water' => null,
                                        'spam' => null,
                                        'tries' => 0,
                                        'analyzerable_id' => $this->id,
                                        'analyzerable_type' => $this->model,
                                    ]);
                                }
                            }
                        } else {
                            $analyzerModel = Analyzer::find($analyzer->id);

                            if ($analyzerModel) {
                                $found = true;

                                $analyzerModel->update([
                                    'status' => Status::SKIPPED->value,
                                    'unique' => null,
                                    'water' => null,
                                    'spam' => null,
                                    'tries' => 0,
                                ]);
                            } else {
                                Analyzer::create([
                                    'status' => Status::SKIPPED->value,
                                    'category' => $this->category,
                                    'unique' => null,
                                    'water' => null,
                                    'spam' => null,
                                    'tries' => 0,
                                    'analyzerable_id' => $this->id,
                                    'analyzerable_type' => $this->model,
                                ]);
                            }
                        }
                    }
                }

                if ($found === false) {
                    $text = AnalyzerCategory::driver($this->category)->text($this->id);

                    if ($text) {
                        try {
                            $taskId = Plagiarism::request($text);

                            $analyzerModel = Analyzer::create([
                                'task_id' => $taskId,
                                'status' => Status::PROCESSING->value,
                                'category' => $this->category,
                                'tries' => 0,
                                'analyzerable_id' => $this->id,
                                'analyzerable_type' => $this->model,
                            ]);
                        } catch (TextShortException $error) {
                            Analyzer::create([
                                'status' => Status::SKIPPED->value,
                                'category' => $this->category,
                                'unique' => null,
                                'water' => null,
                                'spam' => null,
                                'tries' => 0,
                                'analyzerable_id' => $this->id,
                                'analyzerable_type' => $this->model,
                            ]);
                        }
                    } else {
                        Analyzer::create([
                            'status' => Status::SKIPPED->value,
                            'category' => $this->category,
                            'unique' => null,
                            'water' => null,
                            'spam' => null,
                            'tries' => 0,
                            'analyzerable_id' => $this->id,
                            'analyzerable_type' => $this->model,
                        ]);
                    }
                }

                Cache::tags(['analyzer'])->flush();

                if ($analyzerModel) {
                    Launcher::dispatch(
                        'Получение результатов анализа текста',
                        new AnalyzerSaveResultJob($analyzerModel->id),
                        Auth::getUser() ? Auth::getUser()->id : null,
                    )->delay(now()->addMinutes(2));
                }
            }

            return true;
        } catch (PaymentException|LimitException $error) {
            Log::error($error->getMessage());

            return false;
        }
    }
}
