<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return $this->clientRules();
    }

    protected function clientRules(): array
    {
        return [
            'first_name' => ['bail', 'required', 'string', 'min:2', 'max:100'],
            'sir_name' => ['bail', 'required', 'string', 'min:2', 'max:100'],
            'dob' => ['required', 'date_format:d/m/Y', 'before:today'],
            'gender' => ['required', Rule::in(['Male', 'Female', 'Other'])],
            'post_code' => ['nullable', 'string', 'max:20'],
            'country' => ['required', 'string', 'max:100'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'phone' => ['required', 'string', 'regex:/^[0-9+()\-\s]{7,25}$/'],
            'email' => ['required', 'email:rfc', 'max:255'],
            'passport_no' => ['nullable', 'string', 'max:50', 'regex:/^[A-Za-z0-9\-\/]+$/'],
            'visa_type' => ['required', Rule::in(['Appeal', 'Work Visa', 'Student Visa', 'Spouse Visa', 'Visitor Visa', 'Settlement Visa'])],
            'visa_issue_date' => ['required', 'date_format:d/m/Y'],
            'visa_expiry_date' => ['required', 'date_format:d/m/Y', 'after_or_equal:visa_issue_date'],
            'priority' => ['required', Rule::in(['Urgent', 'High', 'Medium', 'Low'])],
            'status' => ['required', Rule::in(['Active', 'Closed', 'Pending', 'Archived'])],
            'court_type' => ['nullable', Rule::in(['Magistrate', 'Crown', 'High Court', 'Tribunal'])],
        ];
    }

    protected function prepareForValidation(): void
    {
        foreach (['first_name', 'sir_name', 'post_code', 'country', 'address', 'city', 'phone', 'email', 'passport_no'] as $field) {
            if ($this->has($field)) {
                $value = trim((string) $this->input($field));
                $this->merge([$field => $value === '' ? null : $value]);
            }
        }
    }
}
