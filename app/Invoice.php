<?php

namespace App;

use App\InvoiceItem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
     use HasFactory;

    protected $fillable = [
        'client_id', 'invoice_no', 'amount', 'invoice_date', 'status', 'description', 'our_ref', 'vat', 'total_due'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function items() {
        return $this->hasMany(InvoiceItem::class);
    }

    public function receipts()
    {
        return $this->hasMany(Receipt::class);
    }
}
