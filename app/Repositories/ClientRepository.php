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
        'address',
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

        $request->validate([
            'first_name' => 'required',
            'surname' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'gender' => 'required',
        ]);

        $client = Client::create([
            'first_name'        => $request->first_name,
            'sir_name'           => $request->sir_name,
            'dob'               => $request->dob,
            'gender'            => $request->gender,
            'email'             => $request->email,
            'phone'             => $request->phone,
            'company_id'        => $request->company_id ?? 1,

            'address'           => $request->address,
            'city'              => $request->city,
            'country'           => $request->country,

            'passport_no'   => $request->passport_no,

            'visa_type'         => $request->visa_type,
            'visa_issue_date'   => $request->visa_issue_date,
            'visa_expiry_date'  => $request->visa_expiry_date,

            'status'            => $request->status,
            'priority'          => $request->priority,
            'court_type'        => $request->court_type,

            'color'             => $request->color,
        ]);

        return $client;
    }
}
