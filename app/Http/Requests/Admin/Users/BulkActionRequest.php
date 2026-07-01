<?php

namespace App\Http\Requests\Admin\Users;

use App\Enums\UserPlan;
use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class BulkActionRequest extends FormRequest
{
    public const ACTIONS = ['assign_plan', 'assign_role', 'verify_email', 'suspend', 'activate', 'delete', 'notify'];

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
            'action' => ['required', Rule::in(self::ACTIONS)],
            'user_ids' => ['required', 'array', 'min:1'],
            'user_ids.*' => ['integer', 'exists:users,id'],
            'plan' => ['required_if:action,assign_plan', new Enum(UserPlan::class)],
            'role' => ['required_if:action,assign_role', new Enum(UserRole::class)],
            'subject' => ['required_if:action,notify', 'string', 'max:255'],
            'body' => ['required_if:action,notify', 'string', 'max:5000'],
        ];
    }
}
