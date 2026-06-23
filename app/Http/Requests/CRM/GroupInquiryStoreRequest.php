<?php

namespace App\Http\Requests\CRM;

use Illuminate\Foundation\Http\FormRequest;

class GroupInquiryStoreRequest extends FormRequest
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
            'host_name' => ['required', 'string', 'max:255'],
            'host_email' => ['required', 'email', 'max:255'],
            'host_phone' => ['nullable', 'string', 'max:50'],
            'event_name' => ['nullable', 'string', 'max:255'],
            'event_date' => ['nullable', 'date', 'after_or_equal:today'],
            'guest_count' => ['nullable', 'integer', 'min:1', 'max:500'],
            'message' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
