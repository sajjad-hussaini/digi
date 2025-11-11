<?php

namespace App\Http\Controllers;

use App\Client;
use App\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index(Client $client)
    {
        $invoices = $client->invoices;
        return view('invoices.index', compact('client', 'invoices'));
    }

    public function create(Client $client)
    {
        return view('invoices.create', compact('client'));
    }

    public function store(Request $request, Client $client)
    {
        $validated = $request->validate([
            'invoice_no' => 'required|unique:invoices',
            'amount' => 'required|numeric',
            'invoice_date' => 'nullable|date',
            'description' => 'nullable|string'
        ]);

        $client->invoices()->create($validated);
        return redirect()->route('clients.invoices.index', $client)->with('success', 'Invoice created successfully.');
    }
}
