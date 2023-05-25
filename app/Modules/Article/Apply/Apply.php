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
        return Article::where('status', Status::READY->value);
    }
}
