<?php

namespace App\Repositories;

use App\Tag;
use App\User;
use App\Client;
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

class ClientRepository extends BaseRepository
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
       $client = Client::create([
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

         return $client;
        
    }

}