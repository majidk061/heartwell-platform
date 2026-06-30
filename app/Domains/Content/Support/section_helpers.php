<?php

use App\Domains\Content\Support\SectionDesignRegistry;

if (! function_exists('section_view')) {
    /**
     * @param  array<string, mixed>  $content
     */
    function section_view(string $sectionType, array $content = []): string
    {
        return SectionDesignRegistry::viewName($sectionType, $content);
    }
}
