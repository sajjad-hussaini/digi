<?php

namespace App\Http\Controllers;

use App\Client;
use App\CustomField;
use App\DataTables\InvoiceDataTable;
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
}
