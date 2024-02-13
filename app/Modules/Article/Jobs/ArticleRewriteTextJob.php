<?php
/**
 * Статьи написанные искусственным интеллектом для разных сущностей.
 * Пакет содержит классы для хранения статей написанных искусственным интеллектом.
 *
 * @package App.Models.Article
 */

namespace App\Modules\Article\Jobs;

use Log;
use Writer;
use Cache;
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
use App\Models\Exceptions\LimitException;

/**
 * Задание на переписание текста для описания курса.
 */
class ArticleRewriteTextJob implements ShouldQueue
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
    private int $id;

    /**
     * Категория.
     *
     * @var string
     */
    private string $category;

    /**
     * Текст для переписания.
     *
     * @var string
     */
    private string $text;

    /**
     * Показатель креативности сети.
     *
     * @var int
     */
    private int $creative;

    /**
     * Конструктор.
     *
     * @param int $id ID статьи.
     * @param string $category Категория.
     * @param string $text Текст для переписания.
     * @param int $creative Показатель креативности сети.
     */
    public function __construct(int $id, string $category, string $text, int $creative = 7)
    {
        $this->id = $id;
        $this->category = $category;
        $this->text = $text;
        $this->creative = $creative;
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
            $action = new ArticleGetAction($this->id);
            $articleEntity = $action->run();

            if ($articleEntity && $articleEntity->status === Status::PENDING) {
                $taskId = Writer::request($this->text, [
                    'rewrite' => true,
                    'strong' => true,
                    'creative' => $this->creative,
                ]);

                Article::find($this->id)->update([
                    'task_id' => $taskId,
                    'request' => $this->text,
                    'status' => Status::PROCESSING->value,
                ]);

                Cache::tags(['article'])->flush();

                ArticleSaveResultJob::dispatch($this->id)
                    ->delay(now()->addMinutes(2));
            }
        } catch (PaymentException|LimitException $error) {
            Log::error($error->getMessage());
        }
    }
}
