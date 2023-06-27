<?php
/**
 * Анализатор текстов для SEO проверки.
 * Пакет содержит классы для хранения результатов анализа текстов для SEO.
 *
 * @package App.Models.Analyzer
 */

namespace App\Modules\Analyzer\Commands;

use Illuminate\Console\Command;
use App\Modules\Course\Models\Course;
use App\Modules\Analyzer\Models\Analyzer;

/**
 * Переведем результаты анализа описания текста в хранилище для анализа текста.
 */
class AnalyzerShiftToCourseTextCommand extends Command
{
    /**
     * Название консольной команды.
     *
     * @var string
     */
    protected $signature = 'analyzer:shift-to-course-text';

    /**
     * Описание консольной команды.
     *
     * @var string
     */
    protected $description = 'Перенос результатов анализа текста в хранилище результатов анализа.';

    /**
     * Выполнение команды.
     *
     * @return void
     */
    public function handle(): void
    {
        $query = Course::whereHas('analyzers')
            ->whereHas('articles')
            ->doesntHave('articles.analyzers');

        $total = $query->count();

        if ($total) {
            $this->line('Запуск переноса...');
            $courses = $query->get();

            $bar = $this->output->createProgressBar($total);
            $bar->start();

            foreach ($courses as $course) {
                /**
                 * @var Course $course
                 */
                foreach ($course->analyzers as $analyzer) {
                    if ($analyzer->category === 'course.text') {
                        foreach ($course->articles as $article) {
                            if ($article->category === 'course.text') {
                                Analyzer::create([
                                    'task_id' => $analyzer->task_id,
                                    'category' => 'article.text',
                                    'unique' => $analyzer->unique,
                                    'water' => $analyzer->water,
                                    'spam' => $analyzer->spam,
                                    'params' => $analyzer->params,
                                    'tries' => $analyzer->tries,
                                    'status' => $analyzer->status,
                                    'analyzerable_id' => $article->id,
                                    'analyzerable_type' => 'App\Modules\Article\Models\Article',
                                ]);
                            }
                        }
                    }
                }

                $bar->advance();
            }

            $bar->finish();

            $this->info("\n\nПеренос осуществлен.");
        } else {
            $this->info("Нет данных для переноса.");
        }
    }
}
