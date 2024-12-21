<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
            'user_type' => ['required', 'string', 'in:Admin,Faculty,Dean,Program-Head'],
            'admin_id' => ['nullable', 'string', 'exists:admin_ids,admin_id'],
            'dean_id' => ['nullable', 'string', 'exists:program_head_dean_ids,identifier'],
            'program_head_id' => ['nullable', 'string', 'exists:program_head_dean_ids,identifier'],
        ];
    }
}
