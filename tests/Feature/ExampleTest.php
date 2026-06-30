<?php

namespace Tests\Feature;

use App\Domains\Content\Enums\ContentStatus;
use App\Domains\Content\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_application_returns_a_successful_response(): void
    {
        Page::query()->create([
            'slug' => 'home',
            'title' => 'Home',
            'status' => ContentStatus::Published,
            'is_published' => true,
            'sort_order' => 1,
        ]);

        $this->get('/')->assertOk();
    }
}
