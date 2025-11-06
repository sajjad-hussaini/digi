<?php

namespace App\Http\Controllers;

use App\Company;
use Illuminate\Http\Request;
use App\DataTables\CompanyDataTable;
use App\Repositories\CompanyRepository;
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
