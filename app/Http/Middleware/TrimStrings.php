<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\TrimStrings as Middleware;

class TrimStrings extends Middleware
{
    /**
     * The names of the attributes that should not be trimmed.
     *
     * @var array
     */
    protected $except = [
        'password',
        'password_confirmation',
        'find_text',
        'replace_text',
    ];
    protected function transform($key, $value)
    {
        if (preg_match('/^replacements\.[^.]+\.(find|replace)$/', $key)) {
            return $value;
        }
        return parent::transform($key, $value);
    }
}
