<?php

namespace App\Http\Requests\Admin\Users;

use App\Enums\UserPlan;
use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->route('user'))],
            'role' => ['required', new Enum(UserRole::class)],
            'plan' => ['required', new Enum(UserPlan::class)],
            'country' => ['nullable', Rule::in(array_keys(config('countries')))],
            'phone' => ['nullable', 'string', 'max:30'],
        ];
    }
}
