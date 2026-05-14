<?php

namespace App\Http\Controllers;

use App\Client;
use App\CustomField;
use App\DataTables\InvoiceDataTable;
use App\Http\Controllers\ReceiptController;
use App\Invoice;
use App\InvoiceItem;
use App\Repositories\InvoiceRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{

    protected $invoiceRepository;

    public function __construct(InvoiceRepository $invoiceRepository)
    {
        $this->invoiceRepository = $invoiceRepository;
    }
    /**
     * Display a listing of the resource.
     */
   public function index(InvoiceDataTable $invoiceDataTable)
    {
         $this->authorize('viewAny', Invoice::class);
        return $invoiceDataTable->render('invoices.index');
    }

    public function create()
    {
        $customFields = CustomField::all();
        $clients = Client::all();
        $invoiceNo = 'INV-' . str_pad(Invoice::max('id') + 1, 4, '0', STR_PAD_LEFT);
        return view('invoices.create', compact('clients', 'customFields', 'invoiceNo'));
    }

    public function store(Request $request)
    {
        // Step 1: Invoice save karein
        $invoice = Invoice::create([
            'client_id'    => $request->client_id,
            'invoice_no'   => $request->invoice_no,
            'invoice_date' => $request->invoice_date,
            'our_ref'      => $request->our_ref,
            'vat'          => $request->vat ?? 0,
            'total_due'    => $request->total_due,
            'amount'    => 2,
            'status'       => 'unpaid',
        ]);

        // Step 2: Invoice items save karein
        foreach ($request->items as $index => $item) {
            InvoiceItem::create([
                'invoice_id'  => $invoice->id,
                'sr_no'       => $index + 1,
                'description' => $item['description'],
                'fees'        => $item['fees'],
            ]);
        }

        return redirect()->route('invoices.show', $invoice->id)
               ->with('success', 'Invoice successfully created!');
    }
    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);
        if($invoice)

        {
            $invoice->delete();
        }

        return redirect()->route('invoices.index');
    }

    public function show($id) {
        $invoice = Invoice::with(['client', 'items',])->findOrFail($id);
        return view('invoices.show', compact('invoice'));
    }

    public function downloadPdf($id) {
        $invoice = Invoice::with(['client', 'items'])->findOrFail($id);
        $pdf = Pdf::loadView('invoices.template', compact('invoice'))
                  ->setPaper('a4');
        return $pdf->download('invoice-'.$invoice->invoice_no.'.pdf');
    }

    // ══════════════════════════════════════════════════════
    // 2. InvoiceController  →  markAsPaid method
    //    (Add this to your existing InvoiceController)
    // ══════════════════════════════════════════════════════
 

    public function markAsPaid(Request $request, Invoice $invoice)
    {
        $request->validate([
            'payment_method' => 'required|in:cash,cheque,bacs,money_order',
            'cheque_number'  => 'nullable|required_if:payment_method,cheque',
        ]);
    
        // 1. Update invoice
        $invoice->update([
            'status'  => 'paid',
            'paid_at' => now(),
        ]);
    
        // 2. Auto-create receipt
        $receipt = ReceiptController::createFromInvoice(
            $invoice,
            $request->payment_method,
            $request->cheque_number
        );
    
        return redirect()
            ->route('receipts.show', $receipt)
            ->with('success', 'Invoice marked as paid. Receipt generated.');
    }


    public function generateInvoiceForClient(Client $client)
    {   
        $customFields = CustomField::all();
        $clients = Client::all();
        $invoiceNo = 'INV-' . str_pad(Invoice::max('id') + 1, 4, '0', STR_PAD_LEFT);
        return view('invoices.create', compact('clients', 'customFields', 'invoiceNo', 'client'));
    }
}
