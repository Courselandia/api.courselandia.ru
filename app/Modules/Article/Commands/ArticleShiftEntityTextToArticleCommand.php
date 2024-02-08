<?php
/**
 * Статьи написанные искусственным интеллектом для разных сущностей.
 * Пакет содержит классы для хранения статей написанных искусственным интеллектом.
 *
 * @package App.Models.Article
 */

namespace App\Modules\Article\Commands;

use App\Modules\Analyzer\Models\Analyzer;
use App\Modules\Article\Models\Article;
use App\Modules\Category\Models\Category;
use App\Modules\Profession\Models\Profession;
use App\Modules\School\Models\School;
use Illuminate\Console\Command;
use App\Modules\Article\Enums\Status as StatusArticle;
use App\Modules\Analyzer\Enums\Status as StatusAnalyzer;
use App\Modules\Direction\Models\Direction;

/**
 * Перенос текстов сущностей в статьи.
 */
class ArticleShiftEntityTextToArticleCommand extends Command
{
    /**
     * Название консольной команды.
     *
     * @var string
     */
    protected $signature = 'article:shift-entity-text-to-article';

    /**
     * Описание консольной команды.
     *
     * @var string
     */
    protected $description = 'Перенос текстов сущностей в статьи.';

    /**
     * Выполнение команды.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->line('Запуск переноса...');

        $this->proceedDirection();
        $this->proceedProfession();
        $this->proceedCategory();
        $this->proceedSchool();

        $this->info("\n\nПеренос осуществлен.");
    }

    /**
     * Перенос направлений.
     *
     * @return void
     */
    private function proceedDirection(): void
    {
        $query = Direction::whereHas('analyzers')
            ->doesntHave('articles')
            ->with('analyzers')
            ->where('text', '!=', '')
            ->whereNotNull('text');

        $total = $query->count();

        if ($total) {
            $this->line('Перенос направлений');

            $directions = $query->get();

            $bar = $this->output->createProgressBar($total);
            $bar->start();

            foreach ($directions as $direction) {
                foreach ($direction->analyzers as $analyzer) {
                    if ($analyzer->category === 'direction.text') {
                        $article = Article::create([
                            'category' => 'direction.text',
                            'text' => $direction->text,
                            'tries' => 1,
                            'status' => $analyzer->unique >= 80 ? StatusArticle::APPLIED->value : StatusArticle::READY->value,
                            'articleable_id' => $direction->id,
                            'articleable_type' => Direction::class,
                        ]);

                        Analyzer::create([
                            'category' => 'article.text',
                            'unique' => $analyzer->unique,
                            'water' => $analyzer->water,
                            'spam' => $analyzer->spam,
                            'tries' => 1,
                            'status' => StatusAnalyzer::READY->value,
                            'analyzerable_id' => $article->id,
                            'analyzerable_type' => Article::class,
                        ]);
                    }
                }

                $bar->advance();
            }

            $bar->finish();
            $this->line("\n");
        }
    }

    /**
     * Перенос направлений.
     *
     * @return void
     */
    private function proceedProfession(): void
    {
        $query = Profession::whereHas('analyzers')
            ->doesntHave('articles')
            ->with('analyzers')
            ->where('text', '!=', '')
            ->whereNotNull('text');

        $total = $query->count();

        if ($total) {
            $this->line('Перенос профессий');

            $professions = $query->get();

            $bar = $this->output->createProgressBar($total);
            $bar->start();

            foreach ($professions as $profession) {
                foreach ($profession->analyzers as $analyzer) {
                    if ($analyzer->category === 'profession.text') {
                        $article = Article::create([
                            'category' => 'profession.text',
                            'text' => $profession->text,
                            'tries' => 1,
                            'status' => $analyzer->unique >= 80 ? StatusArticle::APPLIED->value : StatusArticle::READY->value,
                            'articleable_id' => $profession->id,
                            'articleable_type' => Profession::class,
                        ]);

                        Analyzer::create([
                            'category' => 'article.text',
                            'unique' => $analyzer->unique,
                            'water' => $analyzer->water,
                            'spam' => $analyzer->spam,
                            'tries' => 1,
                            'status' => StatusAnalyzer::READY->value,
                            'analyzerable_id' => $article->id,
                            'analyzerable_type' => Article::class,
                        ]);
                    }
                }

                $bar->advance();
            }

            $bar->finish();
            $this->line("\n");
        }
    }

    /**
     * Перенос категорий.
     *
     * @return void
     */
    private function proceedCategory(): void
    {
        $query = Category::whereHas('analyzers')
            ->doesntHave('articles')
            ->with('analyzers')
            ->where('text', '!=', '')
            ->whereNotNull('text');

        $total = $query->count();

        if ($total) {
            $this->line('Перенос категорий');

            $categories = $query->get();

            $bar = $this->output->createProgressBar($total);
            $bar->start();

            foreach ($categories as $category) {
                foreach ($category->analyzers as $analyzer) {
                    if ($analyzer->category === 'category.text') {
                        $article = Article::create([
                            'category' => 'category.text',
                            'text' => $category->text,
                            'tries' => 1,
                            'status' => $analyzer->unique >= 80 ? StatusArticle::APPLIED->value : StatusArticle::READY->value,
                            'articleable_id' => $category->id,
                            'articleable_type' => Category::class,
                        ]);

                        Analyzer::create([
                            'category' => 'article.text',
                            'unique' => $analyzer->unique,
                            'water' => $analyzer->water,
                            'spam' => $analyzer->spam,
                            'tries' => 1,
                            'status' => StatusAnalyzer::READY->value,
                            'analyzerable_id' => $article->id,
                            'analyzerable_type' => Article::class,
                        ]);
                    }
                }

                $bar->advance();
            }

            $bar->finish();
            $this->line("\n");
        }
    }

    /**
     * Перенос школ.
     *
     * @return void
     */
    private function proceedSchool(): void
    {
        $query = School::whereHas('analyzers')
            ->doesntHave('articles')
            ->with('analyzers')
            ->where('text', '!=', '')
            ->whereNotNull('text');

        $total = $query->count();

        if ($total) {
            $this->line('Перенос школ');

            $schools = $query->get();

            $bar = $this->output->createProgressBar($total);
            $bar->start();

            foreach ($schools as $school) {
                foreach ($school->analyzers as $analyzer) {
                    if ($analyzer->category === 'school.text') {
                        $article = Article::create([
                            'category' => 'school.text',
                            'text' => $school->text,
                            'tries' => 1,
                            'status' => $analyzer->unique >= 80 ? StatusArticle::APPLIED->value : StatusArticle::READY->value,
                            'articleable_id' => $school->id,
                            'articleable_type' => School::class,
                        ]);

                        Analyzer::create([
                            'category' => 'article.text',
                            'unique' => $analyzer->unique,
                            'water' => $analyzer->water,
                            'spam' => $analyzer->spam,
                            'tries' => 1,
                            'status' => StatusAnalyzer::READY->value,
                            'analyzerable_id' => $article->id,
                            'analyzerable_type' => Article::class,
                        ]);
                    }
                }

                $bar->advance();
            }

            $bar->finish();
            $this->line("\n");
        }
    }
}
