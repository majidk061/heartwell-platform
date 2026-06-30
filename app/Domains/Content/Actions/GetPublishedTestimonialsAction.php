<?php

namespace App\Domains\Content\Actions;

use App\Domains\Content\Models\Testimonial;
use Illuminate\Support\Collection;

class GetPublishedTestimonialsAction
{
    /**
     * @param  array<string, mixed>  $settings
     * @return Collection<int, Testimonial>
     */
    public function execute(array $settings = []): Collection
    {
        $home = $settings['home'] ?? [];
        $enabled = $home['testimonials_enabled'] ?? true;

        if ($enabled === false) {
            return collect();
        }

        $count = max(1, min(24, (int) ($home['testimonials_count'] ?? $home['count'] ?? 6)));

        return Testimonial::query()
            ->published()
            ->orderBy('sort_order')
            ->limit($count)
            ->get();
    }
}
