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
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * Метод запуска логики.
     *
     * @return FaqEntity|null Вернет результаты исполнения.
     * @throws ParameterInvalidException
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

                return $faq ? new FaqEntity($faq->toArray()) : null;
            }
        );
    }
}
