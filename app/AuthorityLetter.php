<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthorityLetter extends Model
{
    use HasFactory;

    protected $table = 'authority_letters';

     protected $fillable = [
        'client_id',
        'solicitor_name',
        'full_name',
        'first_name',
        'sir_name',
        'purpose',
        'client_address',
        'passport_no',
        'file_path',
        'date_of_birth',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}