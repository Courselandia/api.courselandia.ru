<?php
/**
 * Модуль Промоакций.
 * Этот модуль содержит все классы для работы с промоакциями.
 *
 * @package App\Modules\Promotion
 */

namespace App\Modules\Promotion\Tests\Feature\Imports;

use App\Modules\Promotion\Imports\Import;
use App\Modules\Promotion\Imports\Parsers\ParserGeekBrains;
use App\Modules\Promotion\Imports\Parsers\ParserNetology;
use App\Modules\Promotion\Imports\Parsers\ParserSkillbox;
use Tests\TestCase;

/**
 * Тестирование: Класс импортирование промоакций.
 */
class ImportTest extends TestCase
{
    /**
     * Импорт промоакций.
     *
     * @return void
     */
    public function testRun(): void
    {
        $import = new Import();
        $import->clearParsers();

        $import->addParser(new ParserNetology(storage_path('test/imports/promotions/netology.json')))
            ->addParser(new ParserGeekBrains(storage_path('test/imports/promotions/geekBrains.json')))
            ->addParser(new ParserSkillbox(storage_path('test/imports/promotions/skillbox.json')));

        $import->run();

        $this->assertFalse($import->hasError());
    }
}
