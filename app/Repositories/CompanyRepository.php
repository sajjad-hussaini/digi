<?php

namespace App\Repositories;

use App\Tag;
use App\User;
use App\Company;
use Laracasts\Flash\Flash;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Spatie\Permission\Models\Permission;
use App\Http\Requests\CreateFilesRequest;

/**
 * Class TagRepository
 * @package App\Repositories
 * @version November 12, 2019, 3:59 pm IST
*/

class CompanyRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'company_name',
        'company_address',
        'contact_number',
        'email_address',
        'solicitor_name',
        'regulated_by',
        'company_reg_number',
        'company_logo',
        'accreditor_logos',
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Company::class;
    }

    public function store($request)
    {
        // store images
        if ($request->hasFile('company_logo')) {
            $companyLogo = $request->file('company_logo')->store('uploads/logo', 'public');
            $request->merge(['company_logo' => $companyLogo]);
        }

        if ($request->hasFile('accreditor_logos')) {
            $accreditorLogos = [];
            foreach ($request->file('accreditor_logos') as $logo) {
                $path = $logo->store('uploads/accre_logo', 'public');
                $accreditorLogos[] = $path;
            }
            $request->merge(['accreditor_logos' => $accreditorLogos]);
        }

        $company = new Company();
        $company->company_name = $request->input('company_name');
        $company->company_address = $request->input('company_address');
        $company->contact_number = $request->input('contact_number');
        $company->email_address = $request->input('email_address');
        $company->solicitor_name = $request->input('solicitor_name');
        $company->regulated_by = $request->input('regulated_by');
        $company->company_reg_number = $request->input('company_reg_number');
        $company->company_logo = $request->input('company_logo');
        $company->accreditor_logos = $request->input('accreditor_logos');
        $company->save();
        
    }

}