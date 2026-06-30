<?php

namespace App\Filament\Concerns;

use App\Domains\Content\Support\CmsImage;
use Filament\Forms;
use Illuminate\Support\HtmlString;

trait ConfiguresCmsImageFields
{
    public static function cmsImagePreviewPlaceholder(string $field, string $label = 'Current image'): Forms\Components\Placeholder
    {
        return Forms\Components\Placeholder::make($field.'_preview')
            ->label($label)
            ->content(function (?object $record) use ($field): HtmlString {
                $path = $record?->{$field} ?? null;

                if (blank($path)) {
                    return new HtmlString('<span class="text-sm text-gray-500">No image uploaded.</span>');
                }

                $url = CmsImage::url($path);

                return new HtmlString(
                    '<div class="space-y-2">'
                    .'<img src="'.e((string) $url).'" alt="" class="max-h-48 rounded-lg border border-gray-200 object-cover" />'
                    .(CmsImage::isExternalUrl($path)
                        ? '<p class="text-xs text-gray-500">External image — upload a new file below to replace it.</p>'
                        : '')
                    .'</div>'
                );
            })
            ->visible(fn (?object $record): bool => filled($record?->{$field}))
            ->columnSpanFull();
    }

    public static function cmsImageUploadField(
        string $field,
        string $label,
        string $directory,
        ?string $helperText = null,
    ): Forms\Components\FileUpload {
        return Forms\Components\FileUpload::make($field)
            ->label($label)
            ->image()
            ->imageEditor()
            ->maxSize(2048)
            ->disk('public')
            ->directory($directory)
            ->visibility('public')
            ->helperText($helperText ?? 'Upload JPG or PNG. Recommended max 2 MB.');
    }
}
