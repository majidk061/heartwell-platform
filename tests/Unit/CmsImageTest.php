<?php

namespace Tests\Unit;

use App\Domains\Content\Support\CmsImage;
use Tests\TestCase;

class CmsImageTest extends TestCase
{
    public function test_detects_external_urls(): void
    {
        $this->assertTrue(CmsImage::isExternalUrl('https://images.unsplash.com/photo-123'));
        $this->assertFalse(CmsImage::isExternalUrl('cms/testimonials/photo.jpg'));
    }

    public function test_passes_through_external_url(): void
    {
        $url = 'https://images.unsplash.com/photo-123';

        $this->assertSame($url, CmsImage::url($url));
    }
}
