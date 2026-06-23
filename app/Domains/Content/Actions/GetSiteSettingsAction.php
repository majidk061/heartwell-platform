<?php

namespace App\Domains\Content\Actions;

use App\Domains\Content\Models\SiteSetting;

class GetSiteSettingsAction
{
    /**
     * @return array<string, mixed>
     */
    public function execute(): array
    {
        $defaults = [
            'brand' => config('heartwell.brand'),
            'navigation' => config('heartwell.navigation'),
            'ctas' => config('heartwell.ctas'),
            'compliance' => config('heartwell.compliance'),
        ];

        $stored = SiteSetting::query()->pluck('value', 'key');

        foreach ($stored as $key => $value) {
            if (is_array($value) && isset($defaults[$key]) && is_array($defaults[$key])) {
                $defaults[$key] = array_replace_recursive($defaults[$key], $value);
            } elseif ($value !== null) {
                $defaults[$key] = $value;
            }
        }

        return $defaults;
    }
}
