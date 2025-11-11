<?php

namespace App\Http\Controllers;

use App\Client;
use App\BalanceStatement;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BalanceStatementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
   public function show(Client $client)
    {
        $balance = $client->balanceStatement;
        return view('balance.show', compact('client', 'balance'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BalanceStatement $balanceStatement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BalanceStatement $balanceStatement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BalanceStatement $balanceStatement)
    {
        //
    }
}
