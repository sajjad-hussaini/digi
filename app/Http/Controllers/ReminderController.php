<?php

namespace App\Http\Controllers;

use App\Client;
use App\Reminder;
use Illuminate\Http\Request;

class ReminderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reminders = Reminder::whereDate('reminder_date', '>=', now())->get();
        return view('reminders.index', compact('reminders'));
    }

    public function store(Request $request, Client $client)
    {
        $validated = $request->validate([
            'type' => 'required|string',
            'reminder_date' => 'required|date',
            'message' => 'nullable|string',
        ]);

        $client->reminders()->create($validated);
        return back()->with('success', 'Reminder added successfully.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Reminder $reminder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reminder $reminder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reminder $reminder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reminder $reminder)
    {
        //
    }
}
