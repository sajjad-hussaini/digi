<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class UpdateTemplateRequest extends StoreTemplateRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:3', 'max:255'],
            'type' => ['required', Rule::in(self::TYPES)],
            'matter_type' => ['required', Rule::in(self::MATTER_TYPES)],
            'doc_file' => ['nullable', 'file', 'mimes:docx', 'max:10240'],
            'edited_html' => ['nullable', 'string', 'max:1000000'],
        ];
    }
}
