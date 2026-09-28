<?php
namespace Tests\Unit;

use App\Http\Requests\StoreInvoiceRequest;
use PHPUnit\Framework\TestCase;

class InvoiceVatTest extends TestCase
{
    public function test_vat_requires_checkbox_and_ignores_submitted_totals(): void
    {
        foreach ([null, '0', '1'] as $enabled) {
            $data = ['items' => [['fees' => 100], ['fees' => 50]], 'vat' => 999, 'total_due' => 9999];
            if ($enabled !== null) $data['apply_vat'] = $enabled;
            $request = StoreInvoiceRequest::create('/', 'POST', $data);
            $totals = $request->invoiceTotals();
            self::assertEquals($enabled === '1' ? 30 : 0, $totals['vat']);
            self::assertEquals($enabled === '1' ? 180 : 150, $totals['total_due']);
        }
    }

    public function test_vat_recalculates_with_fees_and_preserves_rounding(): void
    {
        $request = StoreInvoiceRequest::create('/', 'POST', ['apply_vat' => '1', 'items' => [['fees' => 103]]]);
        self::assertEquals(['vat' => 21, 'total_due' => 124], $request->invoiceTotals());
        $request->merge(['items' => [['fees' => 0]]]);
        self::assertEquals(['vat' => 0, 'total_due' => 0], $request->invoiceTotals());
    }
}
