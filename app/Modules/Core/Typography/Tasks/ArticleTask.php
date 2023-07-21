<?php
/**
 * Модуль ядра системы.
 * Этот модуль содержит все классы для работы с ядром системы.
 *
 * @package App\Modules\Core
 */

namespace App\Modules\Core\Typography\Tasks;

use Typography;
use Illuminate\Database\Eloquent\Builder;
use App\Modules\Article\Models\Article;

/**
 * Типографирование статей.
 */
class ArticleTask extends Task
{
    /**
     * Количество запускаемых заданий.
     *
     * @return int Количество.
     */
    public function count(): int
    {
        return $this->getQuery()->count();
    }

    /**
     * Запуск типографирования текстов.
     *
     * @return void
     */
    public function run(): void
    {
        $articles = $this
            ->getQuery()
            ->clone()
            ->get();

        foreach ($articles as $article) {
            $article->text = Typography::process($article->text);

            $article->save();

            $this->fireEvent('finished', [$article]);
        }
    }

    /**
     * Получить запрос на записи, которые нужно типографировать.
     *
     * @return Builder Построитель запроса.
     */
    private function getQuery(): Builder
    {
        return Article::whereNotNull('text')
            ->where('text', '!=', '')
            ->orderBy('id', 'ASC');
    }
}
