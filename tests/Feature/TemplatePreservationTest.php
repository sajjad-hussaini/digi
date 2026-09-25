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
}
