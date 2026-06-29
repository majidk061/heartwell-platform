<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * @return list<string>
     */
    public static function allPermissions(): array
    {
        return [
            'content.pages.view',
            'content.pages.edit',
            'content.pages.delete',
            'content.pathways.view',
            'content.pathways.edit',
            'content.pathways.delete',
            'content.faqs.view',
            'content.faqs.edit',
            'content.faqs.delete',
            'content.testimonials.view',
            'content.testimonials.edit',
            'content.testimonials.delete',
            'content.avatar_cards.view',
            'content.avatar_cards.edit',
            'content.avatar_cards.delete',
            'content.site_settings.view',
            'content.site_settings.edit',
            'crm.leads.view',
            'crm.leads.edit',
            'crm.leads.delete',
            'crm.waitlist.view',
            'crm.waitlist.edit',
            'crm.consultations.view',
            'crm.consultations.edit',
            'crm.group_inquiries.view',
            'crm.group_inquiries.edit',
            'bookings.view',
            'bookings.edit',
            'automation.rules.view',
            'automation.rules.edit',
            'automation.logs.view',
            'system.integrations.manage',
            'system.mail.manage',
            'system.users.manage',
            'system.email_templates.manage',
            'system.email_notifications.manage',
        ];
    }

    public function run(): void
    {
        foreach (self::allPermissions() as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }
    }
}
