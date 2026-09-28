<?php
namespace Tests\Feature;

use App\VisaType;
use App\Http\Controllers\VisaTypeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class VisaTypeTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'visa_type_test', 'database.connections.visa_type_test' => [
            'driver' => 'sqlite', 'database' => ':memory:', 'prefix' => '',
        ]]);
        $migration = require database_path('migrations/2026_09_28_000000_create_visa_types_table.php');
        $migration->up();
    }

    protected function tearDown(): void
    {
        DB::purge('visa_type_test');
        parent::tearDown();
    }

    public function test_added_type_is_selectable_and_removed_type_is_only_kept_for_existing_records(): void
    {
        self::assertArrayHasKey('Appeal', VisaType::options());
        $controller = new VisaTypeController();
        $controller->store(Request::create('/', 'POST', ['name' => 'Family Visa']));
        self::assertArrayHasKey('Family Visa', VisaType::options());
        self::assertTrue(Validator::make(['visa_type' => 'Family Visa'], ['visa_type' => VisaType::rules()])->passes());
        $controller->destroy(VisaType::where('name', 'Family Visa')->firstOrFail());
        self::assertArrayNotHasKey('Family Visa', VisaType::options());
        self::assertFalse(Validator::make(['visa_type' => 'Family Visa'], ['visa_type' => VisaType::rules()])->passes());
        self::assertArrayHasKey('Family Visa', VisaType::options('Family Visa'));
        self::assertTrue(Validator::make(['visa_type' => 'Family Visa'], ['visa_type' => VisaType::rules('Family Visa')])->passes());
    }

    public function test_duplicate_names_are_rejected(): void
    {
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        (new VisaTypeController())->store(Request::create('/', 'POST', ['name' => 'Appeal']));
    }

    public function test_management_requires_client_update_permission(): void
    {
        $this->assertGuest();
        $this->get('/admin/visa-types')->assertRedirect('/login');
        self::assertSame('can:update clients', (new VisaTypeController())->getMiddleware()[0]['middleware']);
    }
}
