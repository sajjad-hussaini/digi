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
        $selectedCompany = $companies->first()->id ?? null;
        return view('clients.create', compact('customFields', 'companies', 'selectedCompany'));
    }

    public function store(Request $request)
    {
        // store client
        $client = $this->clientRepository->store($request);
        return redirect()->route('clients.show', $client->id)->with('success', 'Client created successfully.');
    }

    public function show(Client $client)
    {
        $customFields = CustomField::get();
        return view('clients.show', compact('client', 'customFields'));
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
        // Law firm details (agar database mein ho to wahan se lo, warna config ya hardcode)
        $lawFirm = config('app.law_firm_name', 'UK Immigration Law');
        $lawFirmAddress = config('app.law_firm_address', '1st floor, 236 St. Helens Road, Bolton BL3 4EB');
        $phone = config('app.law_firm_phone', '07777328028');
        $email = config('app.law_firm_email', 'qureshisalim@yahoo.com');

        // Today's date formatted
        $today = now()->format('jS F Y');

        // Client ke fields ko safely access karo
        $clientName = trim($client->first_name . ' ' . $client->sir_name);
        $clientFullName = $clientName ?: '__________________'; // agar name na ho to blank line

        $data = [
            'client'         => $client,
            'clientName'     => $clientName,
            'clientFullName' => $clientFullName,
            'dob'            => $client->dob ? \Carbon\Carbon::parse($client->dob)->format('d.m.Y') : '__________________',
            'nationality'    => $client->country ?? '__________________',
            'address'        => $client->address ?? '________________________________________________________________',
            'lawFirm'        => $lawFirm,
            'lawFirmAddress' => $lawFirmAddress,
            'phone'          => $phone,
            'email'          => $email,
            'today'          => $today,
        ];

        $pdf = Pdf::loadView('clients.authority-letter', $data);

        return $pdf->stream('Authority_Letter_' . str_replace(' ', '_', $clientFullName) . '.pdf');
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

    public function initialInstructionLetter(Request $request, Client $client)
    {
        // dd($request->all());
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
            'request' => $request,
            'today'  => now()->format('jS F Y')
        ]);

        // Download ya browser mein show karo
        return $pdf->stream('care_Letter_'.$client->first_name.'.pdf');
        // ya ->download() kar sakte ho
    }

    public function eeCareLetter(Request $request, Client $client)
    {
        // Date format kar lo jaise letter mein hai (29th October 2025)
        $formattedDate = $client->created_at->format('jS F Y'); // 29th October 2025

        // Data pass kar rahe hain blade template ko
        $data = [
            'client' => $client,
            'request' => $request,
            'formattedDate' => $formattedDate ?? now()->format('jS F Y'),
            'today' => now()->format('jS F Y'),
        ];

        // PDF generate karo
        $pdf = Pdf::loadView('clients.client_eecare_letter', [
            'client' => $client,
            'request' => $request,
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
        $pdf = Pdf::loadView('clients.client_covering_letter', [
            'client' => $client,
            'today'  => now()->format('jS F Y')
        ]);

        // Download ya browser mein show karo
        return $pdf->stream('care_Letter_'.$client->first_name.'.pdf');
        // ya ->download() kar sakte ho
    }
}
