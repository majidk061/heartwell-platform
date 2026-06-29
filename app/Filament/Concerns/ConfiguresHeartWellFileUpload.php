<?php

namespace App\Filament\Concerns;

use Filament\Forms\Components\FileUpload;

trait ConfiguresHeartWellFileUpload
{
    /**
     * @param  list<string|null>  $aspectRatios
     */
    protected static function configureImageUpload(
        FileUpload $upload,
        array $aspectRatios,
        string $helperText,
        int $maxSizeKb = 2048,
    ): FileUpload {
        return $upload
            ->image()
            ->imageEditor()
            ->imageEditorAspectRatios($aspectRatios)
            ->maxSize($maxSizeKb)
            ->helperText($helperText);
    }
}
