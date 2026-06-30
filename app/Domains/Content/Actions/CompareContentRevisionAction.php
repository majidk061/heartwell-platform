<?php

namespace App\Domains\Content\Actions;

use App\Domains\Content\Enums\ContentStatus;
use App\Domains\Content\Models\ContentRevision;
use BackedEnum;
use Illuminate\Support\Str;

class CompareContentRevisionAction
{
    /**
     * @return list<string>
     */
    public function execute(ContentRevision $revision, ?ContentRevision $previous = null): array
    {
        $current = $this->flattenSnapshot($revision->snapshot ?? []);
        $baseline = $previous !== null
            ? $this->flattenSnapshot($previous->snapshot ?? [])
            : [];

        if ($baseline === []) {
            return ['Initial version'];
        }

        $labels = $this->fieldLabels($revision->revisable_type);
        $changes = [];

        foreach ($current as $key => $newValue) {
            $oldValue = $baseline[$key] ?? null;

            if ($this->valuesEqual($oldValue, $newValue)) {
                continue;
            }

            $label = $labels[$key] ?? Str::headline(str_replace('.', ' ', $key));
            $changes[] = sprintf(
                '%s: "%s" → "%s"',
                $label,
                $this->formatValue($oldValue),
                $this->formatValue($newValue),
            );
        }

        foreach ($baseline as $key => $oldValue) {
            if (array_key_exists($key, $current)) {
                continue;
            }

            $label = $labels[$key] ?? Str::headline(str_replace('.', ' ', $key));
            $changes[] = sprintf(
                '%s: "%s" → (removed)',
                $label,
                $this->formatValue($oldValue),
            );
        }

        return $changes === [] ? ['No field changes'] : $changes;
    }

    public function summarize(ContentRevision $revision, ?ContentRevision $previous = null, int $limit = 120): string
    {
        $changes = $this->execute($revision, $previous);
        $summary = implode('; ', $changes);

        return Str::limit($summary, $limit);
    }

    /**
     * @param  array<string, mixed>  $snapshot
     * @return array<string, mixed>
     */
    protected function flattenSnapshot(array $snapshot, string $prefix = ''): array
    {
        $flat = [];

        foreach ($snapshot as $key => $value) {
            $path = $prefix === '' ? (string) $key : "{$prefix}.{$key}";

            if (is_array($value) && $this->isAssociativeArray($value)) {
                $flat = array_merge($flat, $this->flattenSnapshot($value, $path));
            } else {
                $flat[$path] = $value;
            }
        }

        return $flat;
    }

    /**
     * @param  array<mixed>  $value
     */
    protected function isAssociativeArray(array $value): bool
    {
        if ($value === []) {
            return false;
        }

        return array_keys($value) !== range(0, count($value) - 1);
    }

    protected function valuesEqual(mixed $oldValue, mixed $newValue): bool
    {
        if ($oldValue instanceof BackedEnum) {
            $oldValue = $oldValue->value;
        }

        if ($newValue instanceof BackedEnum) {
            $newValue = $newValue->value;
        }

        return $oldValue == $newValue;
    }

    protected function formatValue(mixed $value): string
    {
        if ($value === null) {
            return '—';
        }

        if ($value instanceof BackedEnum) {
            if ($value instanceof ContentStatus) {
                return $value->label();
            }

            return (string) $value->value;
        }

        if (is_bool($value)) {
            return $value ? 'Yes' : 'No';
        }

        if (is_array($value)) {
            return Str::limit(json_encode($value, JSON_UNESCAPED_UNICODE) ?: '', 60);
        }

        return Str::limit((string) $value, 60);
    }

    /**
     * @return array<string, string>
     */
    protected function fieldLabels(?string $revisableType): array
    {
        $common = [
            'title' => 'Page title',
            'slug' => 'URL slug',
            'status' => 'Status',
            'is_published' => 'Published',
            'meta_title' => 'Browser tab title',
            'meta_description' => 'Search description',
            'og_image' => 'Social share image',
            'robots_index' => 'Search indexing',
            'canonical_url' => 'Canonical URL',
            'focus_keyword' => 'Focus keyword',
            'og_type' => 'Open Graph type',
            'twitter_card' => 'Twitter card',
            'schema_type' => 'Structured data type',
            'include_in_sitemap' => 'Include in sitemap',
            'sitemap_priority' => 'Sitemap priority',
            'sitemap_changefreq' => 'Sitemap change frequency',
            'name' => 'Template name',
            'section_type' => 'Section type',
            'heading' => 'Heading',
            'description' => 'Description',
            'sort_order' => 'Sort order',
            'content.body' => 'Body',
            'content.image_url' => 'Image',
            'content.cta_label' => 'Button label',
            'content.cta_url' => 'Button link',
            'content.subheading' => 'Subheading',
            'layout.background' => 'Background',
            'layout.padding' => 'Padding',
        ];

        return $common;
    }
}
