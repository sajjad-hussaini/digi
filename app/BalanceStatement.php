<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BalanceStatement extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'client_id', 'total_invoiced', 'total_paid', 'balance_due'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
