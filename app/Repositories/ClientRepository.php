<?php

namespace App\Repositories;

use App\Client;
use App\Company;
use App\Http\Requests\CreateFilesRequest;
use App\Repositories\BaseRepository;
use App\Tag;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Laracasts\Flash\Flash;
use Spatie\Permission\Models\Permission;

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
            'sir_name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'gender' => 'required',
            'visa_type' => 'required',
        ]);

        $client = Client::create([
            'first_name'        => $request->first_name,
            'sir_name'           => $request->sir_name,
            'dob'               => Carbon::createFromFormat('d/m/Y', $request->dob)->format('Y-m-d'),
            'gender'            => $request->gender,
            'email'             => $request->email,
            'phone'             => $request->phone,
            'company_id'        => $request->company_id ?? 1,

            'address'           => $request->address,
            'city'              => $request->city,
            'country'           => $request->country,

            'passport_no'   => $request->passport_no,

            'visa_type'         => $request->visa_type,
            'visa_issue_date'   => Carbon::createFromFormat('d/m/Y', $request->visa_issue_date)->format('Y-m-d'),
            'visa_expiry_date'  => Carbon::createFromFormat('d/m/Y', $request->visa_expiry_date)->format('Y-m-d'),

            'status'            => $request->status,
            'priority'          => $request->priority,
            'court_type'        => $request->court_type,

            'color'             => $request->color,
        ]);

        return $client;
    }
}
