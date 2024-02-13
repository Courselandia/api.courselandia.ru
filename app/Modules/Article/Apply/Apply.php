<?php
/**
 * Статьи написанные искусственным интеллектом для разных сущностей.
 * Пакет содержит классы для хранения статей написанных искусственным интеллектом.
 *
 * @package App.Models.Article
 */

namespace App\Modules\Article\Apply;

use App\Models\Error;
use App\Models\Event;
use App\Modules\Article\Enums\Status;
use App\Modules\Article\Jobs\ArticleApplyJob;
use App\Modules\Article\Models\Article;
use Illuminate\Database\Eloquent\Builder;

/**
 * Массовое принятия всех текстов.
 */
class Apply
{
    use Error;
    use Event;

    /**
     * Отфильтровать статьи по нижнему порогу заспамленности.
     *
     * @var int|null
     */
    private ?int $unique;

    /**
     * Отфильтровать статьи по верхнему порогу количества воды.
     *
     * @var int|null
     */
    private ?int $water;

    /**
     * Отфильтровать статьи по верхнему порогу заспамленности.
     *
     * @var int|null
     */
    private ?int $spam;

    /**
     * Конструктор.
     *
     * @param ?int $unique Отфильтровать статьи по нижнему порогу заспамленности.
     * @param ?int $water Отфильтровать статьи по верхнему порогу количества воды.
     * @param ?int $spam Отфильтровать статьи по верхнему порогу заспамленности.
     */
    public function __construct(?int $unique, ?int $water, ?int $spam)
    {
        $this->unique = $unique;
        $this->water = $water;
        $this->spam = $spam;
    }

    /**
     * Вернет общее количество текстов, которые могут быть приняты.
     *
     * @return int
     */
    public function total(): int
    {
        return $this->getQuery()->count();
    }

    /**
     * Запуск принятия всех текстов.
     *
     * @return void
     */
    public function run(): void
    {
        $articles = $this->getQuery()
            ->get();

        foreach ($articles as $article) {
            ArticleApplyJob::dispatch($article->id)
                ->delay(now()->addMinute());

            $this->fireEvent('apply', [$article]);
        }
    }

    /**
     * Вернет запрос на получение статей, которые могут быть приняты.
     *
     * @return Builder Запрос.
     */
    private function getQuery(): Builder
    {
        $query = Article::where('status', Status::READY->value);

        if ($this->unique || $this->water || $this->spam) {
            $query->whereHas('analyzers', function ($query) {
                $query->where('category', 'article.text');
                if ($this->unique) {
                    $query->where('unique', '>=', $this->unique);
                }

                if ($this->water) {
                    $query->where('water', '<', $this->water);
                }

                if ($this->spam) {
                    $query->where('spam', '<', $this->water);
                }
            });
        }

        return $query;
    }
}
