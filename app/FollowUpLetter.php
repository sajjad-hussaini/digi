<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FollowUpLetter extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id', 'subject', 'body', 'file_path', 'sent_date'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
