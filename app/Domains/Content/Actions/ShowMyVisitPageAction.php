<?php

namespace App\Domains\Content\Actions;

class ShowMyVisitPageAction
{
    /**
     * @return array<string, mixed>
     */
    public function execute(): array
    {
        $portalEnabled = (bool) config('integrations.hydreight.enabled')
            && filled(config('integrations.hydreight.portal_url'));

        return [
            'portalEnabled' => $portalEnabled,
            'portalUrl' => config('integrations.hydreight.portal_url'),
            'steps' => [
                [
                    'title' => 'Complete clinical intake',
                    'body' => 'Secure health history, consent, and provider screening through HeartWell\'s clinical portal.',
                    'cta_label' => 'Continue to intake',
                    'cta_route' => 'clinical-intake',
                ],
                [
                    'title' => 'Prepare for your visit',
                    'body' => 'Arrive hydrated, wear comfortable clothing, and bring any questions for your wellness team.',
                    'cta_label' => 'What to expect',
                    'cta_route' => 'your-experience',
                ],
                [
                    'title' => 'Questions before your visit?',
                    'body' => 'HeartWell remains your primary contact for scheduling and support.',
                    'cta_label' => 'Contact us',
                    'cta_route' => 'contact',
                ],
            ],
        ];
    }
}
