<?php
/**
 * Модуль FAQ's.
 * Этот модуль содержит все классы для работы с FAQ's.
 *
 * @package App\Modules\Faq
 */

namespace App\Modules\Faq\Actions\Admin;

use App\Models\Action;
use App\Models\Entity;
use App\Models\Enums\CacheTime;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Rep\RepositoryFilter;
use App\Models\Rep\RepositoryQueryBuilder;
use App\Modules\Faq\Entities\Faq as FaqEntity;
use App\Modules\Faq\Models\Faq;
use Cache;
use JetBrains\PhpStorm\ArrayShape;
use Util;

/**
 * Класс действия для чтения FAQ.
 */
class FaqReadAction extends Action
{
    /**
     * Сортировка данных.
     *
     * @var array|null
     */
    public ?array $sorts = null;

    /**
     * Фильтрация данных.
     *
     * @var array|null
     */
    public ?array $filters = null;

    /**
     * Начать выборку.
     *
     * @var int|null
     */
    public ?int $offset = null;

    /**
     * Лимит выборки выборку.
     *
     * @var int|null
     */
    public ?int $limit = null;

    /**
     * Конструктор.
     *
     * @param  Faq  $faq  Репозиторий FAQ.
     */
    public function __construct(Faq $faq)
    {
        $this->faq = $faq;
    }

    /**
     * Метод запуска логики.
     *
     * @return mixed Вернет результаты исполнения.
     * @throws ParameterInvalidException
     */
    #[ArrayShape(['data' => 'array', 'total' => 'int'])] public function run(): array
    {
        $cacheKey = Util::getKey(
            'faq',
            'admin',
            'read',
            'count',
            $this->filters,
            $this->offset,
            $this->limit,
            [
                'school',
            ]
        );

        return Cache::tags(['catalog', 'school', 'faq'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $query = Faq::filter($this->filters ?: [])
                    ->sorted($this->sorts ?: [])
                    ->with([
                        'school',
                    ]);

                if ($this->offset) {
                    $query->offset($this->offset);
                }

                if ($this->limit) {
                    $query->limit($this->limit);
                }

                $items = $query->get()->toArray();

                return [
                    'data' => Entity::toEntities($items, new FaqEntity()),
                    'total' => $query->count(),
                ];
            }
        );
    }
}
