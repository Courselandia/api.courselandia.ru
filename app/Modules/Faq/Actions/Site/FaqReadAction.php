<?php
/**
 * Модуль FAQ's.
 * Этот модуль содержит все классы для работы с FAQ's.
 *
 * @package App\Modules\Faq
 */

namespace App\Modules\Faq\Actions\Site;

use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Modules\Faq\Entities\Faq as FaqEntity;
use App\Modules\Faq\Models\Faq;
use Cache;
use Util;

/**
 * Класс действия для чтения FAQ.
 */
class FaqReadAction extends Action
{
    /**
     * Ссылка на школу.
     *
     * @var string
     */
    private string $link;

    /**
     * @param string $link Ссылка на школу.
     */
    public function __construct(string $link)
    {
        $this->link = $link;
    }

    /**
     * Метод запуска логики.
     *
     * @return mixed Вернет результаты исполнения.
     */
    public function run(): array
    {
        $cacheKey = Util::getKey(
            'faq',
            'admin',
            'read',
            'count',
            $this->link,
            'school',
        );

        return Cache::tags(['catalog', 'faq'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $query = Faq::whereHas('school', function ($query) {
                    $query->where('link', $this->link);
                })
                ->where('status', 1)
                ->orderBy('question', 'ASC');

                $items = $query->get()->toArray();

                return [
                    'data' => FaqEntity::collect($items),
                ];
            }
        );
    }
}
