<?php
/**
 * Модуль FAQ's.
 * Этот модуль содержит все классы для работы с FAQ's.
 *
 * @package App\Modules\Faq
 */

namespace App\Modules\Faq\Actions\Admin;

use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Rep\RepositoryQueryBuilder;
use App\Modules\Faq\Entities\Faq as FaqEntity;
use App\Modules\Faq\Repositories\Faq;
use Cache;
use Util;

/**
 * Класс действия для получения FAQ.
 */
class FaqGetAction extends Action
{
    /**
     * Репозиторий FAQ.
     *
     * @var Faq
     */
    private Faq $faq;

    /**
     * ID FAQ.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

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
     * @return FaqEntity|null Вернет результаты исполнения.
     * @throws ParameterInvalidException
     */
    public function run(): ?FaqEntity
    {
        $query = new RepositoryQueryBuilder();
        $query->setId($this->id)
            ->setRelations([
                'school',
            ]);

        $cacheKey = Util::getKey('faq', $query);

        return Cache::tags(['catalog', 'school', 'faq'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($query) {
                return $this->faq->get($query);
            }
        );
    }
}
