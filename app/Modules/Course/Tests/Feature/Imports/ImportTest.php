<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Tests\Feature\Imports;

use App\Modules\Course\Imports\Import;
use App\Modules\Course\Imports\Parsers\ParserGeekBrains;
use App\Modules\Course\Imports\Parsers\ParserNetology;
use App\Modules\Course\Imports\Parsers\ParserSkillbox;
use Tests\TestCase;

/**
 * Тестирование: Класс импортирование курсов.
 */
class ImportTest extends TestCase
{
    /**
     * Импорт курсов.
     *
     * @return void
     */
    public function testRun(): void
    {
        $import = new Import();
        $import->clearParsers();

        $import->addParser(new ParserNetology(storage_path('test/imports/netology.xml')))
            ->addParser(new ParserGeekBrains(storage_path('test/imports/geekBrains.xml')))
            ->addParser(new ParserSkillbox(storage_path('test/imports/skillbox.xml')));

        $import->run();

        $this->assertFalse($import->hasError());
    }
}
