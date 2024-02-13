<?php
/**
 * Статьи написанные искусственным интеллектом для разных сущностей.
 * Пакет содержит классы для хранения статей написанных искусственным интеллектом.
 *
 * @package App.Models.Article
 */

namespace App\Modules\Article\Jobs;

use Illuminate\Bus\Queueable;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Article\Actions\Admin\ArticleApplyAction;
use App\Models\Exceptions\ParameterInvalidException;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Задание на принятия текста.
 */
class ArticleApplyJob implements ShouldQueue
{
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use Dispatchable;

    /**
     * ID написанных текстов.
     *
     * @var int
     */
    private int $id;

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
     * @throws RecordNotExistException
     */
    public function handle(): void
    {
        $action = new ArticleApplyAction($this->id);
        $action->run();
    }
}
