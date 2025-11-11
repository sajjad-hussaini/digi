<?php

namespace App\Http\Controllers;

use App\Client;
use App\FollowUpLetter;
use Illuminate\Http\Request;

class FollowUpLetterController extends Controller
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
    public function store(Request $request, Client $client)
    {
        $validated = $request->validate([
            'subject' => 'required|string',
            'body' => 'required|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx'
        ]);

        $path = $request->hasFile('file') ? $request->file('file')->store("clients/{$client->id}/letters", 'public') : null;

        $client->followUpLetters()->create([
            'subject' => $validated['subject'],
            'body' => $validated['body'],
            'file_path' => $path,
            'sent_date' => now(),
        ]);

        return back()->with('success', 'Follow-up letter saved.');
    }

    /**
     * Display the specified resource.
     */
    public function show(FollowUpLetter $followUpLetter)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FollowUpLetter $followUpLetter)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FollowUpLetter $followUpLetter)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FollowUpLetter $followUpLetter)
    {
        //
    }
}
