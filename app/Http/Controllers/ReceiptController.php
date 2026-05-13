<?php
// app/Http/Controllers/ReceiptController.php

namespace App\Http\Controllers;

use App\Client;
use App\DataTables\ReceiptDataTable;
use App\Invoice;
use App\Receipt;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReceiptController extends Controller
{

    /**
    * Display a listing of the resource.
    */
    // public function index(ReceiptDataTable $dataTable)
    // {
    //     $receipts = Receipt::with(['client', 'invoice'])->latest()->paginate(10);
    //     return $dataTable->render('receipts.index', compact('receipts'));
    // }
    // ─────────────────────────────────────────
    // List all receipts
    // ─────────────────────────────────────────
    public function index(Request $request)
    {
        $receipts = Receipt::with(['client', 'invoice'])
            ->when($request->client_id, fn($q) => $q->where('client_id', $request->client_id))
            ->when($request->from,      fn($q) => $q->whereDate('payment_date', '>=', $request->from))
            ->when($request->to,        fn($q) => $q->whereDate('payment_date', '<=', $request->to))
            ->latest('payment_date')
            ->paginate(20);

        $clients = Client::orderBy('name')->get();

        return view('receipts.index', compact('receipts', 'clients'));
    }

    // ─────────────────────────────────────────
    // Show single receipt (HTML view)
    // ─────────────────────────────────────────
    public function show(Receipt $receipt)
    {
        $receipt->load(['client', 'invoice', 'receivedBy']);
        return view('receipts.receipt', compact('receipt'));
    }

    // ─────────────────────────────────────────
    // Download receipt as PDF
    // ─────────────────────────────────────────
    public function downloadPdf(Receipt $receipt)
    {
        $receipt->load(['client', 'invoice', 'receivedBy']);

        $pdf = Pdf::loadView('receipts.receipt', compact('receipt'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont'    => 'Arial',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled'      => true,   // needed if logo is URL
                'dpi'            => 150,
            ]);

        $filename = 'Receipt-' . str_pad($receipt->receipt_number, 4, '0', STR_PAD_LEFT) . '.pdf';

        return $pdf->download($filename);
    }

    // ─────────────────────────────────────────
    // Manual create form
    // ─────────────────────────────────────────
    public function create()
    {
        $clients  = Client::orderBy('name')->get();
        $invoices = Invoice::with('client')->where('status', 'paid')->orWhere('status', 'partial')->get();

        return view('receipts.create', compact('clients', 'invoices'));
    }

    // ─────────────────────────────────────────
    // Store manual receipt
    // ─────────────────────────────────────────
    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_id'     => 'required|exists:invoices,id',
            'client_id'      => 'required|exists:clients,id',
            'amount_paid'    => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,cheque,bacs,money_order',
            'cheque_number'  => 'nullable|string|max:50',
            'payment_date'   => 'required|date',
            'payment_for'    => 'required|string|max:500',
        ]);

        $receipt = Receipt::create([
            ...$validated,
            'receipt_number'  => $this->generateReceiptNumber(),
            'amount_in_words' => $this->numberToWords($validated['amount_paid']),
            'created_by'      => auth()->id(),
        ]);

        return redirect()
            ->route('receipts.show', $receipt)
            ->with('success', 'Receipt created successfully.');
    }

    // ─────────────────────────────────────────
    // Auto-create receipt when invoice marked paid
    // (called from InvoiceController)
    // ─────────────────────────────────────────
    public static function createFromInvoice(Invoice $invoice, string $paymentMethod = 'bacs', $chequeNumber = null): Receipt
    {
        return Receipt::create([
            'invoice_id'      => $invoice->id,
            'client_id'       => $invoice->client_id,
            'receipt_number'  => self::generateReceiptNumberStatic(),
            'ref_number'      => $invoice->invoice_no,
            'amount_paid'     => $invoice->total_due,
            'amount_in_words' => self::numberToWordsStatic($invoice->total_due),
            'payment_method'  => $paymentMethod,
            'payment_date'    => Carbon::now(),
            'payment_for'     => $invoice->description ?? 'Legal Consultancy Fees',
            'created_by'      => auth()->id(),
        ]);
    }

    // ─────────────────────────────────────────
    // Helper: generate receipt number
    // ─────────────────────────────────────────
    private function generateReceiptNumber(): string
    {
        return self::generateReceiptNumberStatic();
    }

    private static function generateReceiptNumberStatic(): string
    {
        $last = Receipt::max('receipt_number');
        return $last ? $last + 1 : 101;
    }

    // ─────────────────────────────────────────
    // Helper: number to words (GBP)
    // ─────────────────────────────────────────
    private function numberToWords(float $amount): string
    {
        return self::numberToWordsStatic($amount);
    }

    private static function numberToWordsStatic(float $amount): string
    {
        $ones = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven',
                 'Eight', 'Nine', 'Ten', 'Eleven', 'Twelve', 'Thirteen',
                 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];
        $tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty',
                 'Sixty', 'Seventy', 'Eighty', 'Ninety'];

        $convert = function (int $n) use (&$convert, $ones, $tens): string {
            if ($n < 20)   return $ones[$n];
            if ($n < 100)  return $tens[(int)($n / 10)] . ($n % 10 ? ' ' . $ones[$n % 10] : '');
            if ($n < 1000) return $ones[(int)($n / 100)] . ' Hundred' . ($n % 100 ? ' ' . $convert($n % 100) : '');
            if ($n < 1000000) {
                return $convert((int)($n / 1000)) . ' Thousand' . ($n % 1000 ? ' ' . $convert($n % 1000) : '');
            }
            return $convert((int)($n / 1000000)) . ' Million' . ($n % 1000000 ? ' ' . $convert($n % 1000000) : '');
        };

        $pounds = (int) $amount;
        $pence  = (int) round(($amount - $pounds) * 100);

        $words = $convert($pounds) . ' Pound' . ($pounds !== 1 ? 's' : '');
        if ($pence > 0) {
            $words .= ' and ' . $convert($pence) . ' Pence';
        }

        return $words . ' Only';
    }
}