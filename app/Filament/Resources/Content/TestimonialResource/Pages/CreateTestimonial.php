<?php

namespace App\Filament\Resources\Content\TestimonialResource\Pages;

use App\Filament\Resources\Content\TestimonialResource;
use App\Filament\Resources\Pages\HeartWellCreateRecord;

class CreateTestimonial extends HeartWellCreateRecord
{
    use \App\Filament\Concerns\HandlesCmsImageUploads;

    protected static string $resource = TestimonialResource::class;
}
