<?php
/**
 * Модуль Разделов.
 * Этот модуль содержит все классы для работы с разделами каталога.
 *
 * @package App\Modules\Section
 */

namespace App\Modules\Section\Actions\Site;

use Cache;
use Util;
use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Section\Entities\Section as SectionEntity;
use App\Modules\Section\Models\Section;
use App\Modules\Salary\Enums\Level;

/**
 * Класс действия для получения раздела.
 */
class SectionLinkAction extends Action
{
    /**
     * Ссылки.
     *
     * @var string[]
     */
    private array $links;

    /**
     * Уровень.
     *
     * @var ?Level
     */
    private ?Level $level;

    /**
     * Признак бесплатности.
     *
     * @var bool
     */
    private bool $free;

    /**
     * @param array $links Ссылки.
     * @param Level|null $level Уровень.
     * @param bool $free Признак бесплатности.
     */
    public function __construct(array $links, ?Level $level = null, bool $free = false)
    {
        $this->links = $links;
        $this->level = $level;
        $this->free = $free;
    }

    /**
     * Метод запуска логики.
     *
     * @return SectionEntity|null Вернет результаты исполнения.
     * @throws ParameterInvalidException
     */
    public function run(): ?SectionEntity
    {
        $cacheKey = Util::getKey(
            'section',
            'site',
            'link',
            $this->links,
            $this->level,
            $this->free,
        );

        return Cache::tags(['section'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $query = Section::active()
                    ->with([
                        'metatag',
                        'items',
                    ]);

                if ($this->level) {
                    $query->where('level', $this->level->value);
                }

                if ($this->free) {
                    $query->where('free', true);
                }

                $weight = 0;

                foreach ($this->links as $link) {
                    $query->whereHas('items.itemable', static function ($query) use ($link, $weight) {
                        $query->where('link', $link)
                            ->where('weight', $weight);
                    });

                    $weight++;
                }

                $result = $query->first();

                return $result ? SectionEntity::from($result->toArray()) : null;
            }
        );
    }
}
