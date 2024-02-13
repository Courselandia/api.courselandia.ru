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
use App\Modules\Faq\Entities\Faq as FaqEntity;
use App\Modules\Faq\Models\Faq;
use Cache;
use Util;

/**
 * Класс действия для получения FAQ.
 */
class FaqGetAction extends Action
{
    /**
     * ID FAQ.
     *
     * @var int|string
     */
    private int|string $id;

    /**
     * @param int|string $id ID FAQ.
     */
    public function __construct(int|string $id)
    {
        $this->id = $id;
    }

    /**
     * Метод запуска логики.
     *
     * @return FaqEntity|null Вернет результаты исполнения.
     */
    public function run(): ?FaqEntity
    {
        $cacheKey = Util::getKey('faq', $this->id, 'school');

        return Cache::tags(['catalog', 'school', 'faq'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $faq = Faq::where('id', $this->id)
                    ->with('school')
                    ->first();

                return $faq ? FaqEntity::from($faq->toArray()) : null;
            }
        );
    }
}
