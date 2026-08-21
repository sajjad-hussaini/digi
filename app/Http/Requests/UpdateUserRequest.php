<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $user = $this->route('user');
        $userId = is_object($user) ? $user->getKey() : $user;

        $rules = [
            'name' => ['bail', 'required', 'string', 'min:2', 'max:255'],
            'email' => ['nullable', 'string', 'email:rfc', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            'username' => ['bail', 'required', 'string', 'min:3', 'max:50', 'alpha_dash', Rule::unique('users', 'username')->ignore($userId)],
            'address' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string', 'max:60000'],
            'password' => ['nullable', 'string', 'min:8', 'max:72', 'confirmed'],
            'status' => ['required', Rule::in([
                config('constants.STATUS.ACTIVE'),
                config('constants.STATUS.BLOCK'),
            ])],
            'global_permissions' => ['nullable', 'array'],
            'global_permissions.*' => ['string', Rule::in($this->allowedGlobalPermissions())],
            'tag_permissions' => ['nullable', 'array'],
            'tag_permissions.*.tag_id' => ['required', 'integer', 'distinct', 'exists:tags,id'],
        ];

        foreach (config('constants.TAG_LEVEL_PERMISSIONS') as $permission) {
            $rules['tag_permissions.*.' . $permission] = ['nullable', 'boolean'];
        }

        return $rules;
    }

    protected function allowedGlobalPermissions()
    {
        return array_merge(...array_values(config('constants.GLOBAL_PERMISSIONS')));
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'name' => $this->filled('name') ? trim($this->input('name')) : null,
            'email' => $this->filled('email') ? strtolower(trim($this->input('email'))) : null,
            'username' => $this->filled('username') ? trim($this->input('username')) : null,
            'address' => $this->filled('address') ? trim($this->input('address')) : null,
        ]);
    }

    public function attributes()
    {
        return [
            'tag_permissions.*.tag_id' => 'tag',
            'global_permissions.*' => 'global permission',
        ];
    }
}
