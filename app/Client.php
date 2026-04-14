<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'sir_name',
        'dob',
        'gender',
        'email',
        'phone',
        'company_id',
        'address',
        'city',
        'country',
        'passport_no',
        'visa_type',
        'visa_issue_date',
        'visa_expiry_date',
        'status',
        'priority',
        'court_type',
        'color'
    ];

    // Relationships
    public function documents()
    {
        return $this->belongsToMany(Document::class, 'clients_documents', 'client_id', 'document_id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function receipts()
    {
        return $this->hasMany(Receipt::class);
    }

    public function ledgerStatements()
    {
        return $this->hasMany(LedgerStatement::class);
    }

    public function balanceStatement()
    {
        return $this->hasOne(BalanceStatement::class);
    }

    public function attendanceNotes()
    {
        return $this->hasMany(AttendanceNote::class);
    }

    public function followUpLetters()
    {
        return $this->hasMany(FollowUpLetter::class);
    }

    public function reminders()
    {
        return $this->hasMany(Reminder::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function authorityLetters()
    {
        return $this->hasMany(AuthorityLetter::class);
    }

      /*
    |--------------------------------------------------------------------------
    | Mutators 
    |--------------------------------------------------------------------------
    */

    public function setDobAttribute($value)
    {
        $this->attributes['dob'] = $value 
            ? Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d') 
            : null;
    }

    public function setVisaIssueDateAttribute($value)
    {
        $this->attributes['visa_issue_date'] = $value 
            ? Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d') 
            : null;
    }

    public function setVisaExpiryDateAttribute($value)
    {
        $this->attributes['visa_expiry_date'] = $value 
            ? Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d') 
            : null;
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors 
    |--------------------------------------------------------------------------
    */

    public function getDobAttribute($value)
    {
        return $value 
            ? Carbon::parse($value)->format('d/m/Y') 
            : null;
    }

    public function getVisaIssueDateAttribute($value)
    {
        return $value 
            ? Carbon::parse($value)->format('d/m/Y') 
            : null;
    }

    public function getVisaExpiryDateAttribute($value)
    {
        return $value 
            ? Carbon::parse($value)->format('d/m/Y') 
            : null;
    }
}
