<?php
/**
 * Статьи написанные искусственным интеллектом для разных сущностей.
 * Пакет содержит классы для хранения статей написанных искусственным интеллектом.
 *
 * @package App.Models.Article
 */

namespace App\Modules\Article\Jobs;

use App\Models\Exceptions\LimitException;
use Log;
use Writer;
use Cache;
use ArticleCategory;
use Illuminate\Bus\Queueable;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\PaymentException;
use App\Modules\Article\Actions\Admin\ArticleGetAction;
use App\Modules\Article\Enums\Status;
use App\Modules\Article\Models\Article;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Задание на написания текста для описания курса.
 */
class ArticleWriteTextJob implements ShouldQueue
{
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use Dispatchable;

    /**
     * ID статьи.
     *
     * @var int
     */
    public int $id;

    /**
     * Категория..
     *
     * @var string
     */
    public string $category;

    /**
     * Конструктор.
     *
     * @param int $id ID статьи.
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
            $action = app(ArticleGetAction::class);
            $action->id = $this->id;
            $articleEntity = $action->run();

            if ($articleEntity && $articleEntity->status === Status::PENDING) {
                $request = ArticleCategory::driver($this->category)->requestTemplate($articleEntity->articleable_id);
                $taskId = Writer::request($request);
                $articleEntity->task_id = $taskId;
                $articleEntity->request = $request;
                $articleEntity->status = Status::PROCESSING;

                Article::find($this->id)->update($articleEntity->toArray());

                Cache::tags(['article'])->flush();

                ArticleSaveResultJob::dispatch($this->id)
                    ->delay(now()->addMinutes(2));
            }
        } catch (PaymentException|LimitException $error) {
            Log::error($error->getMessage());
        }
    }
}
