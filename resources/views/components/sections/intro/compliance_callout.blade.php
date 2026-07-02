@props(['section', 'themeDefaults' => null])

<x-compliance-callout
    :heading="$section->heading"
    :body="$section->body ?? ($section->content['body'] ?? null)"
    :section="$section"
    :theme-defaults="$themeDefaults"
/>
