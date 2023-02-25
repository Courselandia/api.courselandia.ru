<?php
/**
 * Модуль FAQ's.
 * Этот модуль содержит все классы для работы с FAQ's.
 *
 * @package App\Modules\Faq
 */

namespace App\Modules\Faq\Actions\Site;

use App\Models\Action;
use App\Models\Entity;
use App\Models\Enums\CacheTime;
use App\Models\Exceptions\ParameterInvalidException;
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
     * ID школы.
     *
     * @var int|null
     */
    public ?int $school = null;

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
            $this->school,
            'school',
        );

        return Cache::tags(['catalog', 'school', 'faq'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $query = Faq::where('school_id', $this->school)
                    ->where('status', 1)
                    ->orderBy('question', 'ASC');

                $items = $query->get()->toArray();

                return [
                    'data' => Entity::toEntities($items, new FaqEntity()),
                ];
            }
        );
    }
}
