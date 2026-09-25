<?php

namespace Tests\Unit;

use App\Client;
use App\Template;
use App\Http\Controllers\ClientController;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ZipArchive;

class ClientTemplateTest extends TestCase
{
    public function test_literal_ampersands_in_template_text_do_not_break_key_replacement(): void
    {
        $reflection = new ReflectionClass(ClientController::class);
        $controller = $reflection->newInstanceWithoutConstructor();
        $xml = '<w:document xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"><w:body>'
            . '<w:p><w:pPr><w:jc w:val="right"/></w:pPr><w:r><w:rPr><w:sz w:val="24"/></w:rPr>'
            . '<w:t>Terms & Conditions &amp; Fees &#38; Tax: [CLIENT_FIRST_NAME]</w:t>'
            . '</w:r></w:p></w:body></w:document>';
        $result = $reflection->getMethod('replaceTemplateXmlPlaceholders')->invoke($controller, $xml, [
            '[CLIENT_FIRST_NAME]' => 'Ali & Sons',
        ]);
        $document = new \DOMDocument();
        self::assertTrue($document->loadXML($result));
        self::assertSame('Terms & Conditions & Fees & Tax: Ali & Sons', $document->textContent);
        self::assertStringContainsString('<w:jc w:val="right"/>', $result);
        self::assertStringContainsString('<w:sz w:val="24"/>', $result);
    }

    public function test_word_can_export_a_personalized_template_to_pdf(): void
    {
        if (getenv('TEST_WORD_PDF') !== '1') {
            self::markTestSkipped('Set TEST_WORD_PDF=1 on a Windows host with Word to run the export check.');
        }
        $word = new PhpWord();
        $section = $word->addSection();
        $section->addHeader()->addText('Original header');
        $section->addText('[CLIENT_FIRST_NAME]', ['name' => 'Arial', 'size' => 12]);
        $section->addFooter()->addText('Original footer');
        $original = tempnam(sys_get_temp_dir(), 'test_word_');
        $pdf = $original . '.pdf';
        IOFactory::createWriter($word, 'Word2007')->save($original);
        $reflection = new ReflectionClass(ClientController::class);
        $controller = $reflection->newInstanceWithoutConstructor();
        $template = new Template();
        $template->content = file_get_contents($original);
        $client = new Client();
        $client->first_name = 'Test Client';
        $personalized = $reflection->getMethod('createPersonalizedTemplateFile')->invoke($controller, $template, $client);
        $originalPath = getenv('PATH');
        try {
            // Reproduce a web-server environment without PowerShell on PATH.
            putenv('PATH=' . sys_get_temp_dir());
            $reflection->getMethod('convertDocxToPdfWithWord')->invoke($controller, $personalized, $pdf);
            self::assertFileExists($pdf);
            self::assertSame('%PDF-', file_get_contents($pdf, false, null, 0, 5));
            self::assertGreaterThan(1000, filesize($pdf));
        } finally {
            putenv($originalPath === false ? 'PATH' : 'PATH=' . $originalPath);
            unlink($original);
            unlink($personalized);
            if (is_file($pdf)) unlink($pdf);
        }
    }

    public function test_original_template_styles_and_split_keys_are_preserved_for_each_client(): void
    {
        $word = new PhpWord();
        $section = $word->addSection(['marginLeft' => 900]);
        $section->addHeader()->addText('[REFERENCE_NUMBER]');
        $section->addFooter()->addText('[CLIENT_SURNAME]');
        $run = $section->addTextRun(['alignment' => 'right']);
        $run->addText('[CLIENT_', ['name' => 'Times New Roman', 'size' => 17, 'bold' => true]);
        $run->addText('FIRST_NAME]');
        $section->addText('[ADDRESS_1] / [CLIENT_FIRST_NAME] / {{ref_number}}');
        $original = tempnam(sys_get_temp_dir(), 'test_template_');
        IOFactory::createWriter($word, 'Word2007')->save($original);
        $template = new Template();
        $template->content = file_get_contents($original);
        $reflection = new ReflectionClass(ClientController::class);
        $controller = $reflection->newInstanceWithoutConstructor();
        $method = $reflection->getMethod('createPersonalizedTemplateFile');
        $source = new ZipArchive();
        $source->open($original);
        try {
            foreach (['Ali & Sons', 'Sara <Test>'] as $name) {
                $client = new Client();
                $client->first_name = $name;
                $client->sir_name = 'Family';
                $client->address1 = '12 Main Road';
                $client->ref_number = 'REF-123';
                $output = $method->invoke($controller, $template, $client);
                $zip = new ZipArchive();
                $zip->open($output);
                try {
                    $xml = $zip->getFromName('word/document.xml');
                    $dom = new \DOMDocument();
                    self::assertTrue($dom->loadXML($xml));
                    self::assertStringContainsString($name, $dom->textContent);
                    self::assertStringContainsString('12 Main Road', $dom->textContent);
                    self::assertStringContainsString('REF-123', $dom->textContent);
                    self::assertStringNotContainsString('[CLIENT_', $dom->textContent);
                    self::assertStringContainsString('Times New Roman', $xml);
                    self::assertStringContainsString('w:val="34"', $xml);
                    self::assertStringContainsString('w:val="right"', $xml);
                    self::assertSame($source->getFromName('word/styles.xml'), $zip->getFromName('word/styles.xml'));
                    self::assertStringContainsString('REF-123', $zip->getFromName('word/header1.xml'));
                    self::assertStringContainsString('Family', $zip->getFromName('word/footer1.xml'));
                } finally {
                    $zip->close();
                    unlink($output);
                }
            }
            self::assertSame(file_get_contents($original), $template->content);
        } finally {
            $source->close();
            unlink($original);
        }
    }
}
