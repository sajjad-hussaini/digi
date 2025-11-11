<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id', 'type', 'reminder_date', 'message', 'status'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
