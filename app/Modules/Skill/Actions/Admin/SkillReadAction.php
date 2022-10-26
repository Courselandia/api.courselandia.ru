<?php
/**
 * Модуль Навыков.
 * Этот модуль содержит все классы для работы с навыками.
 *
 * @package App\Modules\Skill
 */

namespace App\Modules\Skill\Actions\Admin;

use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Rep\RepositoryFilter;
use App\Models\Rep\RepositoryQueryBuilder;
use App\Modules\Skill\Repositories\Skill;
use Cache;
use JetBrains\PhpStorm\ArrayShape;
use ReflectionException;
use Util;

/**
 * Класс действия для чтения навыков.
 */
class SkillReadAction extends Action
{
    /**
     * Репозиторий навыков.
     *
     * @var Skill
     */
    private Skill $skill;

    /**
     * Поиск данных.
     *
     * @var string|null
     */
    public ?string $search = null;

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
     * @param  Skill  $skill  Репозиторий навыков.
     */
    public function __construct(Skill $skill)
    {
        $this->skill = $skill;
    }

    /**
     * Метод запуска логики.
     *
     * @return mixed Вернет результаты исполнения.
     * @throws ParameterInvalidException|ReflectionException
     */
    #[ArrayShape(['data' => 'array', 'total' => 'int'])] public function run(): array
    {
        $query = new RepositoryQueryBuilder();
        $query->setSearch($this->search)
            ->setFilters(RepositoryFilter::getFilters($this->filters))
            ->setSorts($this->sorts)
            ->setOffset($this->offset)
            ->setLimit($this->limit)
            ->setRelations([
                'metatag',
            ]);

        $cacheKey = Util::getKey('skill', 'read', 'count', $query);

        return Cache::tags(['catalog', 'skill'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($query) {
                return [
                    'data' => $this->skill->read($query),
                    'total' => $this->skill->count($query),
                ];
            }
        );
    }
}
