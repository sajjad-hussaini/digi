<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LedgerStatement extends Model
{
    use HasFactory;

     protected $fillable = [
        'client_id', 'date', 'description', 'debit', 'credit', 'balance'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
