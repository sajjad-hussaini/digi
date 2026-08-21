<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTemplateRequest extends FormRequest
{
    protected const TYPES = ['Authority Letter', 'Initial Instruction', 'Client Care', 'Client Closure Letter', 'Covering Letter'];
    protected const MATTER_TYPES = ['Appeal', 'Work Visa', 'Student Visa', 'Spouse Visa', 'Visitor Visa', 'Settlement Visa'];

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:3', 'max:255'],
            'type' => ['required', Rule::in(self::TYPES)],
            'visa_type' => ['required', Rule::in(self::MATTER_TYPES)],
            'doc_file' => ['required', 'file', 'mimes:docx', 'max:10240'],
        ];
    }
}
