<?php

namespace App\Http\Controllers;

use App\DataTables\ReceiptDataTable;
use App\Invoice;
use App\Receipt;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReceiptController extends Controller
{
    public function index(ReceiptDataTable $dataTable)
    {
        $receipts = Receipt::with(['client', 'invoice'])->latest()->paginate(10);
        return $dataTable->render('receipts.index', compact('receipts'));
    }

    public function create()
    {
        $invoice = Invoice::with(['client', 'payments'])->findOrFail($invoiceId);

        // Auto receipt number
        $lastReceipt = Receipt::latest()->first();
        $receiptNo   = str_pad(($lastReceipt ? $lastReceipt->id + 1 : 1), 4, '0', STR_PAD_LEFT);

        return view('receipts.template', compact('invoice', 'receiptNo'));
    }

    public function generate($invoiceId)
    {
        $invoice = Invoice::with(['client', 'payments'])->findOrFail($invoiceId);

        // Auto receipt number
        $lastReceipt = Receipt::latest()->first();
        $receiptNo   = str_pad(($lastReceipt ? $lastReceipt->id + 1 : 1), 4, '0', STR_PAD_LEFT);

        return view('receipts.create', compact('invoice', 'receiptNo'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'invoice_id'     => 'required|exists:invoices,id',
            'client_id'      => 'required|exists:clients,id',
            'receipt_no'     => 'required',
            'ref_no'         => 'required',
            'date'           => 'required|date',
            'amount'         => 'required|numeric|min:1',
            'amount_in_words'=> 'required|string',
            'for_payment_of' => 'required|string',
            'received_by'    => 'required|string',
            'paid_by'        => 'required|in:cash,cheque,money_order,bacs',
            'cheque_no'      => 'nullable|string',
        ]);

        $receipt = Receipt::create($request->all());

        return redirect()->route('receipts.pdf', $receipt->id);
    }

    public function show($id)
    {
        $receipt = Receipt::with(['client', 'invoice'])->findOrFail($id);
        return view('receipts.show', compact('receipt'));
    }

    public function downloadPdf($id)
    {
        $receipt = Receipt::with(['client', 'invoice'])->findOrFail($id);
        $pdf = Pdf::loadView('receipts.template', compact('receipt'))
                  ->setPaper('a4');
        return $pdf->download('receipt-' . $receipt->receipt_no . '.pdf');
    }
}