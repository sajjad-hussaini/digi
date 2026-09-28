<?php
namespace Tests\Feature;

use App\Http\Controllers\ClientController;
use App\Services\LibreOfficePdfConverter;
use Tests\TestCase;

class TemplatePdfConverterTest extends TestCase
{
    public function test_libreoffice_driver_routes_to_the_server_converter(): void
    {
        config(['documents.pdf_driver' => 'libreoffice']);
        $converter = \Mockery::mock(LibreOfficePdfConverter::class);
        $converter->shouldReceive('convert')->once()->with('input.docx', 'output.pdf');
        $this->app->instance(LibreOfficePdfConverter::class, $converter);
        $reflection = new \ReflectionClass(ClientController::class);
        $reflection->getMethod('convertTemplateToPdf')->invoke($reflection->newInstanceWithoutConstructor(), 'input.docx', 'output.pdf');
    }

    public function test_missing_converter_gives_actionable_setup_error(): void
    {
        config(['documents.libreoffice_binary' => '/nonexistent/libreoffice']);
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Install it and set LIBREOFFICE_BINARY');
        (new LibreOfficePdfConverter())->convert('input.docx', 'output.pdf');
    }
}
