<?php

namespace App\Http\Requests\CRM;

use App\Domains\CRM\Enums\AvatarType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WaitlistStoreRequest extends FormRequest
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
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'interests' => ['nullable', 'array'],
            'interests.*' => ['string', 'max:255'],
            'source_page' => ['nullable', 'string', 'max:255'],
            'avatar_type' => ['nullable', Rule::enum(AvatarType::class)],
        ];
    }
}
