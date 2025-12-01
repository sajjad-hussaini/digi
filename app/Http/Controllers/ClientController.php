<?php

namespace App\Http\Controllers;

use App\Client;
use App\Company;
use App\CustomField;
// use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\DataTables\ClientDataTable;
use App\Repositories\ClientRepository;
use App\Repositories\PermissionRepository;

class ClientController extends Controller
{
        /** @var  CompanyRepository */
    private $clientRepository;
    /** @var PermissionRepository */
    private $permissionRepository;

    public function __construct(ClientRepository $clientRepo,
                                PermissionRepository $permissionRepository)
    {
        $this->clientRepository = $clientRepo;
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(ClientDataTable $clientDataTable)
    {
        $clients = Client::latest()->paginate(10);
         return $clientDataTable->render('clients.index');
    }

    public function create()
    {
        $customFields = CustomField::where('model_type', 'clients')->get();
        $companies = Company::get();
        return view('clients.create', compact('customFields', 'companies'));
    }

    public function store(Request $request)
    {
        // store client
        $client = $this->clientRepository->store($request);
        return redirect()->route('clients.show', $client->id)->with('success', 'Client created successfully.');
    }

    public function show(Client $client)
    {
        return view('clients.show', compact('client'));
    }

    public function edit(Client $client)
    {

        $companies = Company::select('id', 'company_name')->get();
        return view('clients.edit', compact('client', 'companies'));
    }

    public function update(Request $request, Client $client)
    {
        // update client
        $client->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'company_id' => $request->input('company_id'),
            'phone' => $request->input('phone'),
            'passport_no' => $request->input('passport_no'),
            'visa_type' => $request->input('visa_type'),
            'visa_expiry_date' => $request->input('visa_expiry_date'),
            'dob' => $request->input('dob'),
            'country' => $request->input('country'),
            'address' => $request->input('address'),
            'status' => $request->input('status'),
            'priority' => $request->input('priority'),
            'court_type' => $request->input('court_type'),
            'color' => $request->input('color'),
        ]);
        return redirect()->route('clients.index')->with('success', 'Client updated successfully.');
    }

    public function destroy(Client $client)
    {
        $client->delete();
        return back()->with('success', 'Client deleted.');
    }

    public function generateAuthorityLetter(Client $client)
    {
        // Date format kar lo jaise letter mein hai (29th October 2025)
        $formattedDate = $client->created_at->format('jS F Y'); // 29th October 2025

        // Data pass kar rahe hain blade template ko
        $data = [
            'client' => $client,
            'formattedDate' => $formattedDate ?? now()->format('jS F Y'),
            'today' => now()->format('jS F Y'),
        ];

        // PDF generate karo
        $pdf = Pdf::loadView('clients.authority-letter', [
            'client' => $client,
            'today'  => now()->format('jS F Y')
        ]);

        // Download ya browser mein show karo
        return $pdf->stream('Authority_Letter_'.$client->name.'.pdf');
        // ya ->download() kar sakte ho
    }

    public function clientCareLetter(Client $client)
    {
        // Date format kar lo jaise letter mein hai (29th October 2025)
        $formattedDate = $client->created_at->format('jS F Y'); // 29th October 2025

        // Data pass kar rahe hain blade template ko
        $data = [
            'client' => $client,
            'formattedDate' => $formattedDate ?? now()->format('jS F Y'),
            'today' => now()->format('jS F Y'),
        ];

        // PDF generate karo
        $pdf = Pdf::loadView('clients.client_clouser_letter', [
            'client' => $client,
            'today'  => now()->format('jS F Y')
        ]);

        // Download ya browser mein show karo
        return $pdf->stream('care_Letter_'.$client->first_name.'.pdf');
        // ya ->download() kar sakte ho
    }

    public function initialInstructionLetter(Client $client)
    {
        // Date format kar lo jaise letter mein hai (29th October 2025)
        $formattedDate = $client->created_at->format('jS F Y'); // 29th October 2025

        // Data pass kar rahe hain blade template ko
        $data = [
            'client' => $client,
            'formattedDate' => $formattedDate ?? now()->format('jS F Y'),
            'today' => now()->format('jS F Y'),
        ];

        // PDF generate karo
        $pdf = Pdf::loadView('clients.client_instruction', [
            'client' => $client,
            'today'  => now()->format('jS F Y')
        ]);

        // Download ya browser mein show karo
        return $pdf->stream('care_Letter_'.$client->first_name.'.pdf');
        // ya ->download() kar sakte ho
    }

    public function eeCareLetter(Client $client)
    {
        // Date format kar lo jaise letter mein hai (29th October 2025)
        $formattedDate = $client->created_at->format('jS F Y'); // 29th October 2025

        // Data pass kar rahe hain blade template ko
        $data = [
            'client' => $client,
            'formattedDate' => $formattedDate ?? now()->format('jS F Y'),
            'today' => now()->format('jS F Y'),
        ];

        // PDF generate karo
        $pdf = Pdf::loadView('clients.client_eecare_letter', [
            'client' => $client,
            'today'  => now()->format('jS F Y')
        ]);

        // Download ya browser mein show karo
        return $pdf->stream('care_Letter_'.$client->first_name.'.pdf');
        // ya ->download() kar sakte ho
    }

    public function coveringLetter(Client $client)
    {
        // Date format kar lo jaise letter mein hai (29th October 2025)
        $formattedDate = $client->created_at->format('jS F Y'); // 29th October 2025

        // Data pass kar rahe hain blade template ko
        $data = [
            'client' => $client,
            'formattedDate' => $formattedDate ?? now()->format('jS F Y'),
            'today' => now()->format('jS F Y'),
        ];

        // PDF generate karo
        $pdf = Pdf::loadView('clients.client_cover_letter', [
            'client' => $client,
            'today'  => now()->format('jS F Y')
        ]);

        // Download ya browser mein show karo
        return $pdf->stream('care_Letter_'.$client->first_name.'.pdf');
        // ya ->download() kar sakte ho
    }
}
