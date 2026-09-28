<?php

namespace Tests\Feature;

use App\Template;
use App\Http\Controllers\TemplateController;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class TemplatePreservationTest extends TestCase
{
    public function test_metadata_save_keeps_original_document_bytes(): void
    {
        $template = \Mockery::mock(Template::class)->makePartial();
        $template->content = "original-docx\0bytes";
        $template->shouldReceive('save')->once()->andReturn(true);
        $controller = (new \ReflectionClass(TemplateController::class))->newInstanceWithoutConstructor();
        $request = Request::create('/', 'PATCH', ['title' => 'Updated title', 'type' => 'Client Care', 'matter_type' => 'Spouse Visa']);
        $controller->update($request, $template);
        self::assertSame("original-docx\0bytes", $template->content);
        self::assertSame('Updated title', $template->title);
    }

    public function test_old_html_editor_cannot_overwrite_original_document(): void
    {
        $template = \Mockery::mock(Template::class)->makePartial();
        $template->shouldNotReceive('save');
        $controller = (new \ReflectionClass(TemplateController::class))->newInstanceWithoutConstructor();
        $request = Request::create('/', 'PATCH', ['title' => 'Test', 'type' => 'Client Care', 'matter_type' => 'Spouse Visa', 'edited_html' => '<p>Changed</p>']);
        $this->expectException(ValidationException::class);
        $controller->update($request, $template);
    }

    public function test_replacement_and_removal_preserve_document_styles(): void
    {
        $word = new \PhpOffice\PhpWord\PhpWord();
        $section = $word->addSection();
        $run = $section->addTextRun();
        $run->addText('Old ', ['bold' => true]);
        $run->addText('word / Old word');
        $section->addHeader()->addText('Old word');
        $section->addFooter()->addText('Old word');
        $path = tempnam(sys_get_temp_dir(), 'replace_test_');
        try {
            \PhpOffice\PhpWord\IOFactory::createWriter($word, 'Word2007')->save($path);
            $original = file_get_contents($path);
            $zip = new \ZipArchive();
            $zip->open($path);
            $styles = $zip->getFromName('word/styles.xml');
            $zip->close();
            foreach (['New & <text>', ''] as $replacement) {
                $template = \Mockery::mock(Template::class)->makePartial();
                $template->content = $original;
                $template->shouldReceive('save')->once()->andReturn(true);
                $controller = (new \ReflectionClass(TemplateController::class))->newInstanceWithoutConstructor();
                $request = Request::create('/', 'PATCH', ['title' => 'Test', 'type' => 'Client Care', 'matter_type' => 'Spouse Visa', 'find_text' => 'Old word', 'replace_text' => $replacement]);
                $controller->update($request, $template);
                file_put_contents($path, $template->content);
                $zip->open($path);
                self::assertSame($styles, $zip->getFromName('word/styles.xml'));
                foreach (['document', 'header1', 'footer1'] as $part) {
                    $dom = new \DOMDocument();
                    self::assertTrue($dom->loadXML($zip->getFromName('word/' . $part . '.xml')));
                    self::assertStringNotContainsString('Old word', $dom->textContent);
                    self::assertSame($part === 'document' ? $replacement . ' / ' . $replacement : $replacement, $dom->textContent);
                }
                self::assertStringContainsString('<w:b', $zip->getFromName('word/document.xml'));
                $zip->close();
            }
            [$unchanged, $count] = (new \App\Services\TemplateTextReplacer())->replaceDocument($original, 'missing', 'new');
            self::assertSame(0, $count);
            self::assertSame($original, $unchanged);
        } finally {
            unlink($path);
        }
    }

    public function test_multiple_keys_are_saved_together_without_replacing_new_values_again(): void
    {
        $word = new \PhpOffice\PhpWord\PhpWord();
        $section = $word->addSection();
        $run = $section->addTextRun();
        $run->addText('[NA', ['bold' => true]);
        $run->addText('ME] / [CITY] / REMOVE');
        $section->addHeader()->addText('[CITY]');
        $path = tempnam(sys_get_temp_dir(), 'batch_replace_');
        try {
            \PhpOffice\PhpWord\IOFactory::createWriter($word, 'Word2007')->save($path);
            $original = file_get_contents($path);
            $template = \Mockery::mock(Template::class)->makePartial();
            $template->content = $original;
            $template->shouldReceive('save')->once()->andReturn(true);
            $rows = [
                ['find' => '[NAME]', 'replace' => '[CITY] & Ali'],
                ['find' => '[CITY]', 'replace' => ' Lahore '],
                ['find' => 'REMOVE', 'replace' => ''],
                ['find' => '', 'replace' => ''],
            ];
            $controller = (new \ReflectionClass(TemplateController::class))->newInstanceWithoutConstructor();
            $request = Request::create('/', 'PATCH', ['title' => 'Batch', 'type' => 'Client Care', 'matter_type' => 'Spouse Visa', 'replacements' => $rows]);
            (new \App\Http\Middleware\TrimStrings())->handle($request, function ($request) use ($controller, $template) {
                return $controller->update($request, $template);
            });
            file_put_contents($path, $template->content);
            $zip = new \ZipArchive();
            $zip->open($path);
            $dom = new \DOMDocument();
            $dom->loadXML($zip->getFromName('word/document.xml'));
            self::assertSame('[CITY] & Ali /  Lahore  / ', $dom->textContent);
            $dom->loadXML($zip->getFromName('word/header1.xml'));
            self::assertSame(' Lahore ', $dom->textContent);
            $zip->close();

            // A missing key must reject the entire batch, even if earlier keys match.
            $failed = \Mockery::mock(Template::class)->makePartial();
            $failed->content = $original;
            $failed->shouldNotReceive('save');
            $request->merge(['replacements' => array_merge($rows, [['find' => 'missing', 'replace' => 'new']])]);
            try {
                $controller->update($request, $failed);
                self::fail('Missing keys should reject the batch.');
            } catch (ValidationException $exception) {
                self::assertArrayHasKey('replacements.4.find', $exception->errors());
                self::assertSame($original, $failed->content);
            }
        } finally {
            unlink($path);
        }
    }

    public function test_duplicate_find_rows_are_rejected_before_saving(): void
    {
        $template = \Mockery::mock(Template::class)->makePartial();
        $template->shouldNotReceive('save');
        $controller = (new \ReflectionClass(TemplateController::class))->newInstanceWithoutConstructor();
        $request = Request::create('/', 'PATCH', ['title' => 'Batch', 'type' => 'Client Care', 'matter_type' => 'Spouse Visa', 'replacements' => [
            ['find' => '[NAME]', 'replace' => 'Ali'],
            ['find' => '[NAME]', 'replace' => 'Sara'],
        ]]);
        $this->expectException(ValidationException::class);
        $controller->update($request, $template);
    }
}
