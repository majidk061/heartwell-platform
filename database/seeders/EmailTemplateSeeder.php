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
                'body' => '<p>Hi {{first_name}},</p><p>Your appointment on {{booking_date}} is confirmed.</p><p>Before your visit, please complete your secure clinical intake through HeartWell.</p>',
                'button_label' => 'Complete clinical intake',
                'button_url' => url('/clinical-intake'),
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
                'body' => '<p>Hi {{name}},</p><p>You have been invited to manage the HeartWell website. Click the button below to set your password.</p><p>If the button does not work, copy and paste this link into your browser:<br><a href="{{reset_url}}">{{reset_url}}</a></p><p>This link expires in 60 minutes.</p>',
                'button_label' => 'Set your password',
                'button_url' => '{{reset_url}}',
            ],
            [
                'key' => 'clinical_intake_reminder',
                'name' => 'Booking — Clinical intake reminder',
                'audience' => 'user',
                'subject' => 'Complete your HeartWell clinical intake',
                'heading' => 'One step before your visit',
                'body' => '<p>Hi {{first_name}},</p><p>Your wellness visit on {{booking_date}} is coming up. Please complete your secure clinical intake so we can prepare for your appointment.</p>',
                'button_label' => 'Complete clinical intake',
                'button_url' => '{{clinical_intake_url}}',
            ],
            [
                'key' => 'appointment_reminder',
                'name' => 'Booking — Appointment reminder',
                'audience' => 'user',
                'subject' => 'Reminder: your HeartWell visit is tomorrow',
                'heading' => 'We look forward to seeing you',
                'body' => '<p>Hi {{first_name}},</p><p>This is a friendly reminder about your HeartWell wellness visit on {{booking_date}}.</p>',
            ],
            [
                'key' => 'post_visit_followup',
                'name' => 'Booking — Post-visit follow-up',
                'audience' => 'user',
                'subject' => 'Thank you for visiting HeartWell',
                'heading' => 'How are you feeling?',
                'body' => '<p>Hi {{first_name}},</p><p>Thank you for your visit today. We hope you are feeling supported — reach out anytime if you have questions about your wellness plan.</p>',
            ],
            [
                'key' => 'booking_pending_clearance_admin',
                'name' => 'Booking — Pending clearance admin alert',
                'audience' => 'admin',
                'subject' => 'Booking needs clinical intake: {{email}}',
                'heading' => 'Clinical clearance pending',
                'body' => '<p><strong>Client:</strong> {{first_name}} {{last_name}} ({{email}})<br><strong>Visit:</strong> {{booking_date}}<br><strong>Status:</strong> {{clearance_status}}</p><p>Confirm intake is completed before the visit.</p>',
            ],
            [
                'key' => 'clinical_clearance_renewal',
                'name' => 'CRM — Clinical clearance renewal',
                'audience' => 'user',
                'subject' => 'Time to renew your HeartWell clinical clearance',
                'heading' => 'Your clearance has expired',
                'body' => '<p>Hi {{first_name}},</p><p>Your clinical clearance with HeartWell has expired. Please complete a new intake before your next visit.</p>',
                'button_label' => 'Renew clinical intake',
                'button_url' => '{{clinical_intake_url}}',
            ],
            [
                'key' => 'group_followup_resources',
                'name' => 'Group inquiry — Follow-up resources',
                'audience' => 'user',
                'subject' => 'Planning your HeartWell group gathering',
                'heading' => 'Next steps for your group experience',
                'body' => '<p>Hi {{host_name}},</p><p>Thank you again for your group wellness inquiry. Our team will follow up with availability and planning details soon.</p>',
            ],
            [
                'key' => 'waitlist_nurture_day3',
                'name' => 'Waitlist — Nurture day 3',
                'audience' => 'user',
                'subject' => 'A gentle check-in from HeartWell',
                'heading' => 'We are thinking of you',
                'body' => '<p>Hi {{first_name}},</p><p>While you wait for appointments to open, explore our Support Pathways to learn how HeartWell can support your wellness journey.</p>',
                'button_label' => 'Explore pathways',
                'button_url' => url('/support-pathways'),
            ],
            [
                'key' => 'waitlist_nurture_day7',
                'name' => 'Waitlist — Nurture day 7',
                'audience' => 'user',
                'subject' => 'Ready to connect with HeartWell?',
                'heading' => 'We are here when you are ready',
                'body' => '<p>Hi {{first_name}},</p><p>If you would like personal guidance before booking, request a consultation — we will reach out personally.</p>',
                'button_label' => 'Request consultation',
                'button_url' => url('/contact').'#consultation',
            ],
            [
                'key' => 'lead_contacted_followup',
                'name' => 'CRM — Contacted follow-up',
                'audience' => 'user',
                'subject' => 'Following up from HeartWell',
                'heading' => 'Checking in',
                'body' => '<p>Hi {{first_name}},</p><p>We wanted to follow up on your interest in HeartWell. Reply anytime or request a consultation when you are ready.</p>',
            ],
            [
                'key' => 'lead_completed_nurture',
                'name' => 'CRM — Post-visit nurture',
                'audience' => 'user',
                'subject' => 'Continuing your wellness journey',
                'heading' => 'Thank you for trusting HeartWell',
                'body' => '<p>Hi {{first_name}},</p><p>We hope your visit was supportive. Visit your visit hub for next steps and reach out with any questions.</p>',
                'button_label' => 'Your visit hub',
                'button_url' => '{{my_visit_url}}',
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
