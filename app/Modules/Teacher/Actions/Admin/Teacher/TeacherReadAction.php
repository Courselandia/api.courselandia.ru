<?php
/**
 * Модуль Учителей.
 * Этот модуль содержит все классы для работы с учителями.
 *
 * @package App\Modules\Teacher
 */

namespace App\Modules\Teacher\Actions\Admin\Teacher;

use Util;
use Cache;
use ReflectionException;
use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Modules\Teacher\Models\Teacher;
use App\Modules\Teacher\Entities\TeacherSimple as TeacherEntity;

/**
 * Класс действия для чтения учителя.
 */
class TeacherReadAction extends Action
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
     * Получать фотографии учителей.
     *
     * @var bool
     */
    private bool $showPhoto = true;

    /**
     * @param array|null $sorts Сортировка данных.
     * @param array|null $filters Фильтрация данных.
     * @param int|null $offset Начать выборку.
     * @param int|null $limit Лимит выборки выборку.
     * @param bool $showPhoto Получать фотографии учителей..
     */
    public function __construct(
        array $sorts = null,
        ?array $filters = null,
        ?int $offset = null,
        ?int $limit = null,
        bool $showPhoto = true,
    ) {
        $this->sorts = $sorts;
        $this->filters = $filters;
        $this->offset = $offset;
        $this->limit = $limit;
        $this->showPhoto = $showPhoto;
    }

    /**
     * Метод запуска логики.
     *
     * @return mixed Вернет результаты исполнения.
     * @throws ReflectionException
     */
    public function run(): array
    {
        $cacheKey = Util::getKey(
            'teacher',
            'admin',
            'read',
            'count',
            $this->sorts,
            $this->filters,
            $this->offset,
            $this->limit,
            $this->showPhoto,
            'metatag',
        );

        return Cache::tags(['catalog', 'teacher'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $query = Teacher::filter($this->filters ?: []);

                if ($this->showPhoto) {
                    $select = [
                        'id',
                        'name',
                        'image_small_id',
                        'image_middle_id',
                        'status',
                    ];
                } else {
                    $select = [
                        'id',
                        'name',
                    ];
                }

                $query->select($select);

                $queryCount = $query->clone();

                $query->sorted($this->sorts ?: []);

                if ($this->offset) {
                    $query->offset($this->offset);
                }

                if ($this->limit) {
                    $query->limit($this->limit);
                }

                $items = $query->get()->toArray();

                return [
                    'data' => TeacherEntity::collect($items),
                    'total' => $queryCount->count(),
                ];
            }
        );
    }
}
