<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'first_name', 'sir_name', 'company_id', 'phone', 'passport_no', 'visa_type', 'visa_expiry_date', 'status', 'priority', 'court_type', 'color', 'dob', 'address', 'country'
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
}