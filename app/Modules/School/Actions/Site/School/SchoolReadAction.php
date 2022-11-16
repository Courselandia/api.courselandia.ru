<?php
/**
 * Модуль Школ.
 * Этот модуль содержит все классы для работы со школами.
 *
 * @package App\Modules\School
 */

namespace App\Modules\School\Actions\Site\School;

use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Rep\RepositoryQueryBuilder;
use App\Modules\School\Repositories\School;
use Cache;
use JetBrains\PhpStorm\ArrayShape;
use ReflectionException;
use Util;

/**
 * Класс действия для чтения школ.
 */
class SchoolReadAction extends Action
{
    /**
     * Репозиторий школ.
     *
     * @var School
     */
    private School $school;

    /**
     * Сортировка данных.
     *
     * @var array|null
     */
    public ?array $sorts = null;

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
     * @param  School  $school  Репозиторий школ.
     */
    public function __construct(School $school)
    {
        $this->school = $school;
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
        $query->setSorts($this->sorts)
            ->setOffset($this->offset)
            ->setLimit($this->limit)
            ->setActive(true)
            ->setRelations([
                'metatag',
            ]);

        $cacheKey = Util::getKey('school', 'read', 'count', $query);

        return Cache::tags(['catalog', 'school', 'teacher', 'review', 'faq'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($query) {
                return [
                    'data' => $this->school->read($query),
                    'total' => $this->school->count($query),
                ];
            }
        );
    }
}
