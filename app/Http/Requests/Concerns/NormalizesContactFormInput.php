<?php

namespace App\Http\Requests\Concerns;

trait NormalizesContactFormInput
{
    protected function normalizePersonNameFromSingleField(): void
    {
        if ($this->filled('name') && ! $this->filled('first_name')) {
            $parts = preg_split('/\s+/', trim((string) $this->input('name')), 2) ?: [];

            $this->merge([
                'first_name' => $parts[0] ?? '',
                'last_name' => $parts[1] ?? null,
            ]);
        }
    }

    protected function normalizeAvatarSelection(): void
    {
        $interests = $this->input('avatar_interests', []);

        if (! is_array($interests)) {
            return;
        }

        $interests = array_values(array_filter($interests));

        if ($interests === []) {
            return;
        }

        $this->merge([
            'interests' => $interests,
            'avatar_type' => $this->input('avatar_type') ?? $interests[0],
        ]);
    }

    protected function normalizeGroupInquiryAliases(): void
    {
        if ($this->filled('email') && ! $this->filled('host_email')) {
            $this->merge(['host_email' => $this->input('email')]);
        }

        if ($this->filled('phone') && ! $this->filled('host_phone')) {
            $this->merge(['host_phone' => $this->input('phone')]);
        }

        if ($this->filled('event_details') && ! $this->filled('message')) {
            $this->merge(['message' => $this->input('event_details')]);
        }

        if ($this->filled('details') && ! $this->filled('message')) {
            $this->merge(['message' => $this->input('details')]);
        }

        if ($this->filled('name') && ! $this->filled('host_name')) {
            $this->merge(['host_name' => $this->input('name')]);
        }
    }
}
