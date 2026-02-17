<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    use HasFactory;

    protected $table = 'templates';

    protected $fillable = ['title', 'content'];

    // LONGBLOB ke liye binary handling
    public function getContentAttribute($value)
    {
        // Already binary hai, as-is return karo
        return is_resource($value) ? stream_get_contents($value) : $value;
    }
}
