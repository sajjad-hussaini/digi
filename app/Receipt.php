<?php
// app/Models/Receipt.php

namespace App;

use App\Client;
use App\Invoice;
use App\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    use HasFactory;

    protected $table = 'receiptes'; // matches the migration

    protected $fillable = [
        'invoice_id',
        'client_id',
        'receipt_number',
        'ref_number',
        'amount_paid',
        'amount_in_words',
        'payment_method',    // cash | cheque | bacs | money_order
        'cheque_number',
        'payment_date',
        'payment_for',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount_paid'  => 'decimal:2',
    ];

    // ── Relationships ──────────────────────
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function receivedBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}