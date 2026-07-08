<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClinicalIntakeTest extends TestCase
{
    use RefreshDatabase;

    public function test_clinical_intake_page_renders_heartwell_messaging(): void
    {
        $this->get(route('clinical-intake'))
            ->assertOk()
            ->assertSee('You\'re still with HeartWell', false)
            ->assertSee('HeartWell coordinates everything', false)
            ->assertSee('HeartWell remains your primary point of contact', false)
            ->assertSee('Hydreight clinical workflow', false);
    }

    public function test_clinical_intake_shows_portal_button_when_url_configured(): void
    {
        config([
            'integrations.hydreight.enabled' => true,
            'integrations.hydreight.portal_url' => 'https://portal.example.test/intake',
        ]);

        $this->get(route('clinical-intake'))
            ->assertOk()
            ->assertSee('Continue to Secure Clinical Portal', false)
            ->assertSee('https://portal.example.test/intake', false);
    }

    public function test_clinical_intake_shows_fallback_when_portal_not_configured(): void
    {
        config([
            'integrations.hydreight.enabled' => false,
            'integrations.hydreight.portal_url' => null,
        ]);

        $this->get(route('clinical-intake'))
            ->assertOk()
            ->assertSee('Your HeartWell team will send a secure portal link', false);
    }
}
