<?php
/**
 * Модуль Промокодов.
 * Этот модуль содержит все классы для работы с промокодами.
 *
 * @package App\Modules\Promocode
 */

namespace App\Modules\Promocode\Tests\Feature\Imports;

use App\Modules\Promocode\Imports\Import;
use App\Modules\Promocode\Imports\Parsers\ParserGeekBrains;
use App\Modules\Promocode\Imports\Parsers\ParserNetology;
use App\Modules\Promocode\Imports\Parsers\ParserSkillbox;
use Tests\TestCase;

/**
 * Тестирование: Класс импортирование промокодов.
 */
class ImportTest extends TestCase
{
    /**
     * Импорт промокодов.
     *
     * @return void
     */
    public function testRun(): void
    {
        $import = new Import();
        $import->clearParsers();

        $import->addParser(new ParserNetology(storage_path('test/imports/promocodes/netology.json')))
            ->addParser(new ParserGeekBrains(storage_path('test/imports/promocodes/geekBrains.json')))
            ->addParser(new ParserSkillbox(storage_path('test/imports/promocodes/skillbox.json')));

        $import->run();

        $this->assertFalse($import->hasError());
    }
}
