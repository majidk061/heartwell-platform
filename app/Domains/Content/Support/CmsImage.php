<?php

namespace App\Domains\Content\Support;

use Illuminate\Support\Facades\Storage;

class CmsImage
{
    public static function isExternalUrl(?string $path): bool
    {
        if (blank($path)) {
            return false;
        }

        return str_starts_with($path, 'http://') || str_starts_with($path, 'https://');
    }

    public static function isStoredPath(?string $path): bool
    {
        return filled($path) && ! self::isExternalUrl($path);
    }

    public static function url(?string $path): ?string
    {
        if (empty($path)) {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        $path = ltrim($path, '/');

        if (str_starts_with($path, 'storage/')) {
            $path = substr($path, strlen('storage/'));
        }

        if (Storage::disk('public')->exists($path)) {
            $url = Storage::disk('public')->url($path);
            $fullPath = Storage::disk('public')->path($path);
            $version = @filemtime($fullPath);

            if ($version) {
                $url .= (str_contains($url, '?') ? '&' : '?').'v='.$version;
            }

            return $url;
        }

        return asset('storage/'.$path);
    }
}
