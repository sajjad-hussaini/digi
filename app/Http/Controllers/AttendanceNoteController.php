<?php

namespace App\Http\Controllers;

use App\Client;
use App\AttendanceNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceNoteController extends Controller
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
        $validated = $request->validate(['note' => 'required|string']);

        $client->attendanceNotes()->create([
            'note' => $validated['note'],
            'created_by' => Auth::id(),
        ]);

        return back()->with('success', 'Attendance note added.');
    }

    /**
     * Display the specified resource.
     */
    public function show(AttendanceNote $attendanceNote)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AttendanceNote $attendanceNote)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AttendanceNote $attendanceNote)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AttendanceNote $attendanceNote)
    {
        //
    }
}
