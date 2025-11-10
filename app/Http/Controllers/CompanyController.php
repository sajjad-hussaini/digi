<?php

namespace App\Http\Controllers;

use App\Company;
use App\CustomField;
use Illuminate\Http\Request;
use App\DataTables\CompanyDataTable;
use App\Repositories\CompanyRepository;
use Illuminate\Support\Facades\Storage;
use App\Repositories\PermissionRepository;

class CompanyController extends Controller
{

     /** @var  CompanyRepository */
    private $companyRepository;
    /** @var PermissionRepository */
    private $permissionRepository;

    public function __construct(CompanyRepository $companyRepo,
                                PermissionRepository $permissionRepository)
    {
        $this->companyRepository = $companyRepo;
        $this->permissionRepository = $permissionRepository;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index(CompanyDataTable $companyDataTable)
    {
        $this->authorize('viewAny', Company::class);
        return $companyDataTable->render('companies.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customFields = CustomField::where('model_type', 'tags')->get();
        return view('companies.create', compact('customFields'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // store company
        $this->companyRepository->store($request);
        return redirect()->route('companies.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $company = Company::findOrFail($id);
        $this->authorize('view', $company);
        return view('companies.show', compact('company'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $company = Company::findOrFail($id);
        $this->authorize('update', $company);
        $customFields = CustomField::where('model_type', 'tags')->get();
        return view('companies.edit', compact('company', 'customFields'));
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, string $id)
    {
        $company = Company::findOrFail($id);
        $this->authorize('update', $company);

        // ✅ Handle Company Logo
        if ($request->hasFile('company_logo')) {
            // Delete old logo if exists
            if (!empty($company->company_logo) && Storage::disk('public')->exists($company->company_logo)) {
                Storage::disk('public')->delete($company->company_logo);
            }

            // Upload new one
            $company->company_logo = $request->file('company_logo')->store('uploads/company_logos', 'public');
        }

        if ($request->hasFile('accreditor_logos')) {
            // ✅ Delete old logos safely
            if (!empty($company->accreditor_logos)) {
                $oldLogos = is_array($company->accreditor_logos)
                    ? $company->accreditor_logos
                    : json_decode($company->accreditor_logos, true);

                if (is_array($oldLogos)) {
                    foreach ($oldLogos as $logo) {
                        if (!empty($logo) && Storage::disk('public')->exists($logo)) {
                            Storage::disk('public')->delete($logo);
                        }
                    }
                }
            }

            // ✅ Upload new logos
            $uploadedLogos = [];
            foreach ($request->file('accreditor_logos') as $file) {
                $uploadedLogos[] = $file->store('uploads/accre_logo', 'public');
            }

            $company->accreditor_logos = $uploadedLogos;
        }


        // ✅ Update other company info
        $company->update([
            'company_name' => $request->input('company_name'),
            'company_address' => $request->input('company_address'),
            'contact_number' => $request->input('contact_number'),
            'email_address' => $request->input('email_address'),
            'solicitor_name' => $request->input('solicitor_name'),
            'regulated_by' => $request->input('regulated_by'),
            'company_reg_number' => $request->input('company_reg_number'),
            'company_logo' => $company->company_logo ?? $company->getOriginal('company_logo'),
            'accreditor_logos' => $company->accreditor_logos ?? $company->getOriginal('accreditor_logos'),
        ]);

        return redirect()
            ->route('companies.index')
            ->with('success', 'Company updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $company = Company::findOrFail($id);
        $this->authorize('delete', $company);
        // delete all images
        if ($company->company_logo) {
            Storage::disk('public')->delete($company->company_logo);
        }
        if ($company->accreditor_logos) {
            foreach ($company->accreditor_logos as $logo) {
                Storage::disk('public')->delete($logo);
            }
        }
        $company->delete();
        return redirect()->route('companies.index');

    }
}
