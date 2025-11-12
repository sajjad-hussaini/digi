<?php

namespace App\Http\Controllers;

use App\Client;
use App\Company;
use App\CustomField;
use Illuminate\Http\Request;
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
        $this->clientRepository->store($request);
        return redirect()->route('clients.index')->with('success', 'Client created successfully.');
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
}
