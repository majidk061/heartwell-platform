<?php

namespace Database\Seeders;

use App\Domains\Integrations\Models\EmailTemplate;
use Illuminate\Database\Seeder;

class EmailTemplateSeeder extends Seeder
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public static function defaults(): array
    {
        return [
            [
                'key' => 'waitlist_welcome',
                'name' => 'Waitlist — User welcome',
                'audience' => 'user',
                'subject' => 'Welcome to the HeartWell waitlist',
                'heading' => 'Thank you for joining us',
                'body' => '<p>Hi {{first_name}},</p><p>Thank you for joining the HeartWell waitlist. We will be in touch when appointments open in your area.</p>',
            ],
            [
                'key' => 'waitlist_admin_notify',
                'name' => 'Waitlist — Admin notification',
                'audience' => 'admin',
                'subject' => 'New waitlist signup: {{email}}',
                'heading' => 'New waitlist entry',
                'body' => '<p><strong>Name:</strong> {{first_name}} {{last_name}}<br><strong>Email:</strong> {{email}}<br><strong>Phone:</strong> {{phone}}</p>',
            ],
            [
                'key' => 'consultation_ack',
                'name' => 'Consultation — User acknowledgement',
                'audience' => 'user',
                'subject' => 'We received your consultation request',
                'heading' => 'Thank you for reaching out',
                'body' => '<p>Hi {{first_name}},</p><p>We received your consultation request and will respond soon.</p>',
            ],
            [
                'key' => 'consultation_admin_notify',
                'name' => 'Consultation — Admin notification',
                'audience' => 'admin',
                'subject' => 'New consultation request from {{email}}',
                'heading' => 'New consultation request',
                'body' => '<p><strong>Name:</strong> {{first_name}} {{last_name}}<br><strong>Email:</strong> {{email}}<br><strong>Message:</strong> {{message}}</p>',
            ],
            [
                'key' => 'group_inquiry_ack',
                'name' => 'Group inquiry — User acknowledgement',
                'audience' => 'user',
                'subject' => 'We received your group inquiry',
                'heading' => 'Thank you for your interest',
                'body' => '<p>Hi {{host_name}},</p><p>We received your group wellness gathering inquiry for {{event_name}}.</p>',
            ],
            [
                'key' => 'group_inquiry_admin_notify',
                'name' => 'Group inquiry — Admin notification',
                'audience' => 'admin',
                'subject' => 'New group inquiry: {{event_name}}',
                'heading' => 'New group inquiry',
                'body' => '<p><strong>Host:</strong> {{host_name}} ({{email}})<br><strong>Event:</strong> {{event_name}}<br><strong>Date:</strong> {{event_date}}<br><strong>Guests:</strong> {{guest_count}}<br><strong>Message:</strong> {{message}}</p>',
            ],
            [
                'key' => 'booking_confirmation',
                'name' => 'Booking — User confirmation',
                'audience' => 'user',
                'subject' => 'Your HeartWell appointment is confirmed',
                'heading' => 'Appointment confirmed',
                'body' => '<p>Hi {{first_name}},</p><p>Your appointment on {{booking_date}} is confirmed.</p>',
            ],
            [
                'key' => 'booking_admin_notify',
                'name' => 'Booking — Admin notification',
                'audience' => 'admin',
                'subject' => 'New booking: {{email}}',
                'heading' => 'New Acuity booking',
                'body' => '<p><strong>Client:</strong> {{first_name}} {{last_name}}<br><strong>Email:</strong> {{email}}<br><strong>Date:</strong> {{booking_date}}</p>',
            ],
            [
                'key' => 'new_lead_admin_notify',
                'name' => 'CRM — New lead notification',
                'audience' => 'admin',
                'subject' => 'New lead: {{email}}',
                'heading' => 'New CRM lead',
                'body' => '<p><strong>Name:</strong> {{first_name}} {{last_name}}<br><strong>Email:</strong> {{email}}<br><strong>Source:</strong> {{source}}</p>',
            ],
            [
                'key' => 'admin_invite',
                'name' => 'Admin — Team invite',
                'audience' => 'user',
                'subject' => 'Set your HeartWell admin password',
                'heading' => 'Welcome to HeartWell Admin',
                'body' => '<p>Hi {{name}},</p><p>You have been invited to manage the HeartWell website. Click the button below to set your password.</p>',
                'button_label' => 'Set your password',
                'button_url' => '{{reset_url}}',
            ],
        ];
    }

    public function run(): void
    {
        foreach (self::defaults() as $template) {
            EmailTemplate::query()->updateOrCreate(
                ['key' => $template['key']],
                array_merge([
                    'footer_text' => 'HeartWell Aesthetics & Wellness',
                    'is_enabled' => true,
                ], $template),
            );
        }
    }
}
