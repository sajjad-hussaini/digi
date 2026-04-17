<?php

namespace App\Http\Controllers;

use App\Client;
use App\CustomField;
use App\DataTables\InvoiceDataTable;
use App\Invoice;
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

    public function store(Request $request, Client $client)
    {
 
        Invoice::create([
            'client_id' => $request->input('client_id'),
            'invoice_no' => $request->input('invoice_no'),
            'amount' => $request->input('amount'),
            'invoice_date' => $request->input('invoice_date'),
            'status' => $request->input('status'),
            'description' => $request->input('description'),
        ]);
        return redirect()->route('invoices.index', $client)->with('success', 'Invoice created successfully.');
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
        $invoice = Invoice::with(['client', 'items'])->findOrFail($id);
        return view('invoices.show', compact('invoice'));
    }

    public function downloadPdf($id) {
        $invoice = Invoice::with(['client', 'items'])->findOrFail($id);
        $pdf = Pdf::loadView('invoices.template', compact('invoice'))
                  ->setPaper('a4');
        return $pdf->download('invoice-'.$invoice->invoice_no.'.pdf');
    }
}
