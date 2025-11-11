<?php

namespace App\Http\Controllers;

use App\Client;
use App\LedgerStatement;
use Illuminate\Http\Request;

class LedgerStatementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Client $client)
    {
        $ledger = $client->ledgerStatements;
        return view('ledger.index', compact('client', 'ledger'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(LedgerStatement $ledgerStatement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LedgerStatement $ledgerStatement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LedgerStatement $ledgerStatement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LedgerStatement $ledgerStatement)
    {
        //
    }
}
