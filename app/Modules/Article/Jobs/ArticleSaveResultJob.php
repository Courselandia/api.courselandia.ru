<?php
/**
 * Статьи написанные искусственным интеллектом для разных сущностей.
 * Пакет содержит классы для хранения статей написанных искусственным интеллектом.
 *
 * @package App.Models.Article
 */

namespace App\Modules\Article\Jobs;

use Log;
use Throwable;
use Writer;
use Cache;
use Illuminate\Bus\Queueable;
use App\Modules\Article\Models\Article;
use App\Models\Exceptions\ProcessingException;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Article\Actions\Admin\ArticleGetAction;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Modules\Article\Enums\Status;

class ArticleSaveResultJob implements ShouldQueue
{
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use Dispatchable;

    private const MAX_TRIES = 5;

    /**
     * ID написанных текстов (модель Article).
     *
     * @var int|null
     */
    public ?int $id = null;

    /**
     * Конструктор.
     *
     * @param int $id ID модели написанных текстов.
     */
    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * Выполнение задачи.
     *
     * @return void
     * @throws ParameterInvalidException
     */
    public function handle(): void
    {
        $action = app(ArticleGetAction::class);
        $action->id = $this->id;
        $articleEntity = $action->run();

        if ($articleEntity && $articleEntity->status === Status::PROCESSING) {
            try {
                $text = Writer::result($articleEntity->task_id);
                $articleEntity->text = $text;
                $articleEntity->status = Status::READY;
                $articleEntity->tries = $articleEntity->tries + 1;

                Article::find($this->id)->update($articleEntity->toArray());
            } catch (ProcessingException $error) {
                if ($articleEntity->tries < self::MAX_TRIES) {
                    $articleEntity->tries = $articleEntity->tries + 1;

                    ArticleSaveResultJob::dispatch($this->id)
                        ->delay(now()->addMinutes(2));
                } else {
                    Log::error('Достигнуто максимальное количество попыток получить написанный текст. ID: ' . $this->id . '. Task ID: ' . $articleEntity->task_id);
                    $articleEntity->status = Status::FAILED;
                }

                Article::find($this->id)->update($articleEntity->toArray());
            } catch (Throwable $error) {
                Log::error('Ошибка получения написанного текста: ' . $error->getMessage() . '. ID: ' . $this->id . '. Task ID: ' . $articleEntity->task_id);
                $articleEntity->status = Status::FAILED;
                Article::find($this->id)->update($articleEntity->toArray());
            }

            Cache::tags(['article'])->flush();
        }
    }
}
