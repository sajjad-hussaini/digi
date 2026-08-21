<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'client_id' => ['required', 'integer', 'exists:clients,id'],
            'invoice_no' => ['required', 'string', 'max:50', 'unique:invoices,invoice_no'],
            'our_ref' => ['nullable', 'string', 'max:100'],
            'invoice_date' => ['required', 'date'],
            'items' => ['required', 'array', 'min:1', 'max:100'],
            'items.*.description' => ['required', 'string', 'max:1000'],
            'items.*.fees' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'vat' => ['nullable', 'numeric', 'min:0', 'max:99999999.99'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'invoice_no' => trim((string) $this->input('invoice_no')),
            'our_ref' => $this->filled('our_ref') ? trim((string) $this->input('our_ref')) : null,
        ]);
    }
}
