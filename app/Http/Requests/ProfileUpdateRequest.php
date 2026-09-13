<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'prefix' => ['nullable', 'string', 'max:10'],
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'suffix' => ['nullable', 'string', 'max:10'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username,' . $this->user()->id],
            'sex' => ['required', 'string', 'in:Male,Female'],
            'mobile_number' => ['required', 'string', 'max:20'],
            'birthdate' => ['required', 'date'],
            'religion' => ['nullable', 'string', 'max:255'],
            'disability' => ['nullable', 'string', 'max:255'],
            'ethnic_group' => ['nullable', 'string', 'max:255'],
            'position' => ['nullable', 'string', 'max:255'],
            'department_id' => ['required', 'exists:departments,id'],
            'office_id' => ['required', 'exists:offices,id'],
            'province_id' => ['required', 'exists:provinces,province_id'],
            'city_id' => ['required', 'exists:cities,city_id'],
            'barangay_id' => ['required', 'exists:barangays,id'],
            'street' => ['required', 'string', 'max:255'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'], // 2MB Max
        ];
    }
}
