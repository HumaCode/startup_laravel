<?php

namespace App\Http\Requests\Konfigurasi;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'          => 'required',
            'username'      => [Rule::unique('users')->ignore($this->user)],
            'email'         => [Rule::unique('users')->ignore($this->user)],
            'password'      => [Rule::requiredIf(function () {
                return request()->routeIs('konfigurasi.users.store');
            }), 'confirmed']
        ];
    }
}
