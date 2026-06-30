<?php

namespace App\Filament\Resources\Content\TestimonialResource\Pages;

use App\Filament\Resources\Content\TestimonialResource;
use App\Filament\Resources\Pages\HeartWellEditRecord;

class EditTestimonial extends HeartWellEditRecord
{
    use \App\Filament\Concerns\HandlesCmsImageUploads;

    protected static string $resource = TestimonialResource::class;
}
