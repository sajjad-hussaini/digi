<?php

namespace App\Http\Controllers;

use App\Client;
use App\CustomField;
use App\DataTables\InvoiceDataTable;
use App\Http\Controllers\ReceiptController;
use App\Invoice;
use App\InvoiceItem;
use App\Http\Requests\StoreInvoiceRequest;
use App\Repositories\InvoiceRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function store(StoreInvoiceRequest $request)
    {
        $data = $request->validated();
        $invoice = DB::transaction(function () use ($data) {
            $subtotal = collect($data['items'])->sum(fn ($item) => (float) $item['fees']);
            $vat = (float) ($data['vat'] ?? 0);

            $invoice = Invoice::create([
                'client_id' => $data['client_id'],
                'invoice_no' => $data['invoice_no'],
                'invoice_date' => $data['invoice_date'],
                'our_ref' => $data['our_ref'] ?? null,
                'vat' => $vat,
                'total_due' => $subtotal + $vat,
                'amount' => $subtotal,
                'status' => 'unpaid',
            ]);

            foreach ($data['items'] as $index => $item) {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'sr_no' => $index + 1,
                    'description' => $item['description'],
                    'fees' => $item['fees'],
                ]);
            }

            return $invoice;
        });

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
