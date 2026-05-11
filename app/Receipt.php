<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    use HasFactory;

     protected $fillable = [
        'invoice_id', 'client_id', 'receipt_no', 'ref_no',
        'date', 'amount', 'amount_in_words', 'for_payment_of',
        'received_by', 'paid_by', 'cheque_no',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
