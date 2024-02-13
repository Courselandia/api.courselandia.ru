<?php
/**
 * Статьи написанные искусственным интеллектом для разных сущностей.
 * Пакет содержит классы для хранения статей написанных искусственным интеллектом.
 *
 * @package App.Models.Article
 */

namespace App\Modules\Article\Actions\Admin;

use Cache;
use Util;
use ArticleCategory;
use App\Models\Action;
use ReflectionException;
use App\Models\Enums\CacheTime;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Article\Models\Article;
use App\Modules\Article\Entities\Article as ArticleEntity;

/**
 * Класс действия для чтения статьи.
 */
class ArticleReadAction extends Action
{
    /**
     * Сортировка данных.
     *
     * @var array|null
     */
    private ?array $sorts;

    /**
     * Фильтрация данных.
     *
     * @var array|null
     */
    private ?array $filters;

    /**
     * Начать выборку.
     *
     * @var int|null
     */
    private ?int $offset;

    /**
     * Лимит выборки выборку.
     *
     * @var int|null
     */
    private ?int $limit;

    /**
     * @param array|null $sorts Сортировка данных.
     * @param array|null $filters Фильтрация данных.
     * @param int|null $offset Начать выборку.
     * @param int|null $limit Лимит выборки выборку.
     */
    public function __construct(
        array  $sorts = null,
        ?array $filters = null,
        ?int   $offset = null,
        ?int   $limit = null
    )
    {
        $this->sorts = $sorts;
        $this->filters = $filters;
        $this->offset = $offset;
        $this->limit = $limit;
    }

    /**
     * Метод запуска логики.
     *
     * @return mixed Вернет результаты исполнения.
     * @throws ParameterInvalidException|ReflectionException
     */
    public function run(): array
    {
        $cacheKey = Util::getKey(
            'article',
            'admin',
            'read',
            'count',
            $this->sorts,
            $this->filters,
            $this->offset,
            $this->limit,
        );

        return Cache::tags(['article'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $query = Article::filter($this->filters ?: [])
                    ->with([
                        'articleable.analyzers',
                        'analyzers',
                    ]);

                $queryCount = $query->clone();

                $query->sorted($this->sorts ?: []);

                if ($this->offset) {
                    $query->offset($this->offset);
                }

                if ($this->limit) {
                    $query->limit($this->limit);
                }

                $items = $query->get()->toArray();

                for ($i = 0; $i < count($items); $i++) {
                    $field = ArticleCategory::driver($items[$i]['category'])->field();
                    $items[$i]['category_name'] = ArticleCategory::driver($items[$i]['category'])->name();
                    $items[$i]['category_label'] = ArticleCategory::driver($items[$i]['category'])->label($items[$i]['articleable_id']);
                    $items[$i]['text_current'] = $items[$i]['articleable'][$field];
                    $items[$i]['request_template'] = ArticleCategory::driver($items[$i]['category'])->requestTemplate($items[$i]['articleable_id']);

                    for ($z = 0; $z < count($items[$i]['articleable']['analyzers']); $z++) {
                        $category = $items[$i]['articleable']['analyzers'][$z]['category'];
                        $items[$i]['articleable']['analyzers'][$z]['category_name'] = ArticleCategory::driver($category)->name();
                        $items[$i]['articleable']['analyzers'][$z]['category_label'] = ArticleCategory::driver($category)->label($items[$i]['articleable_id']);
                    }
                }

                return [
                    'data' => ArticleEntity::collection($items),
                    'total' => $queryCount->count(),
                ];
            }
        );
    }
}
