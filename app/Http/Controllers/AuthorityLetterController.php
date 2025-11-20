<?php

namespace App\Http\Controllers;

use App\Client;
use App\AuthorityLetter;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class AuthorityLetterController extends Controller
{
    public function index(Client $client)
    {
        return view('authority_letters.index', [
            'client' => $client,
            'letters' => $client->authorityLetters
        ]);
    }

    public function create(Client $client)
    {
        return view('authority_letters.create', compact('client'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_id'       => 'required',
            'solicitor_name'  => 'required',
            'firm_name'       => 'required',
            'purpose'         => 'required',
            'client_address'  => 'required',
            'passport_no'     => 'required',
        ]);

        $data = AuthorityLetter::create([
            'client_id'      => $request->client_id,
            'solicitor_name' => $request->solicitor_name,
            'firm_name'      => $request->firm_name,
            'purpose'        => $request->purpose,
            'client_address' => $request->client_address,
            'passport_no'    => $request->passport_no,
            'date'           => now()->format('Y-m-d'),
        ]);

        // Generate PDF
        $pdf = Pdf::loadView('authority_letters.pdf', ['data' => $data]);

        $fileName = 'authority_letter_' . time() . '.pdf';
        $path = 'documents/' . $fileName;

        Storage::disk('public')->put($path, $pdf->output());

        $data->update(['file_path' => $path]);

        return redirect()->route('authorityLetter.index', $data->client_id)
            ->with('success', 'Authority Letter Created Successfully!');
    }
}