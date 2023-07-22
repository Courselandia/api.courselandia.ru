<?php
/**
 * Статьи написанные искусственным интеллектом для разных сущностей.
 * Пакет содержит классы для хранения статей написанных искусственным интеллектом.
 *
 * @package App.Models.Article
 */

namespace App\Modules\Article\Actions\Admin;

use App\Models\Action;
use App\Modules\Analyzer\Entities\Analyzer as AnalyzerEntity;
use App\Modules\Analyzer\Enums\Status;
use App\Modules\Analyzer\Models\Analyzer;
use App\Models\Exceptions\ParameterInvalidException;

/**
 * Класс для переноса готовых результатов анализа со статей в указанную сущность.
 */
class ArticleMoveAnalyzer extends Action
{
    /**
     * ID модели куда переносим результат анализа.
     *
     * @var int|string
     */
    private int|string $id;

    /**
     * Набор готовых анализаторов.
     *
     * @var AnalyzerEntity[]
     */
    private array $analyzers;

    /**
     * Название категории анализатора.
     *
     * @var string
     */
    private string $category;

    /**
     * Модель, куда переносится анализатор.
     *
     * @var string
     */
    private string $model;

    /**
     * Конструктор.
     *
     * @param int|string $id ID модели куда переносим результат анализа.
     * @param AnalyzerEntity[] $analyzers Набор готовых анализаторов.
     * @param string $category Название категории анализатора.
     * @param string $model Модель, куда переносится анализатор.
     */
    public function __construct(int|string $id, array $analyzers, string $category, string $model)
    {
        $this->id = $id;
        $this->analyzers = $analyzers;
        $this->category = $category;
        $this->model = $model;
    }

    /**
     * Метод запуска логики.
     *
     * @return bool Признако того что перенос состоялся.
     * @throws ParameterInvalidException
     */
    public function run(): bool
    {
        if ($this->analyzers) {
            foreach ($this->analyzers as $analyzer) {
                if ($analyzer->category === $this->category && $analyzer->status === Status::READY) {
                    $analyzerModel = Analyzer::where('analyzerable_id', $this->id)
                        ->where('category', $this->category)
                        ->first();

                    if ($analyzerModel) {
                        $analyzerModel->unique = $analyzer->unique;
                        $analyzerModel->water = $analyzer->water;
                        $analyzerModel->spam = $analyzer->spam;
                        $analyzerModel->tries = $analyzer->tries;
                        $analyzerModel->status = Status::READY->value;

                        $analyzerModel->save();

                        return true;
                    } else {
                        $analyzerModel = new AnalyzerEntity();
                        $analyzerModel->task_id = $analyzer->task_id;
                        $analyzerModel->category = $this->category;
                        $analyzerModel->unique = $analyzer->unique;
                        $analyzerModel->water = $analyzer->water;
                        $analyzerModel->spam = $analyzer->spam;
                        $analyzerModel->tries = $analyzer->tries;
                        $analyzerModel->status = $analyzer->status;
                        $analyzerModel->analyzerable_id = $this->id;
                        $analyzerModel->analyzerable_type = $this->model;

                        Analyzer::create($analyzerModel->toArray());

                        return true;
                    }
                }
            }
        }

        return false;
    }
}
