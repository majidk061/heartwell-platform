<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(PermissionSeeder::class);

        $allPermissions = PermissionSeeder::allPermissions();

        $superAdmin = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $superAdmin->syncPermissions($allPermissions);

        $editor = Role::firstOrCreate(['name' => 'editor', 'guard_name' => 'web']);
        $editor->syncPermissions([
            'content.pages.view',
            'content.pages.edit',
            'content.pathways.view',
            'content.pathways.edit',
            'content.faqs.view',
            'content.faqs.edit',
            'content.testimonials.view',
            'content.testimonials.edit',
            'content.avatar_cards.view',
            'content.avatar_cards.edit',
            'content.site_settings.view',
            'content.site_settings.edit',
            'crm.leads.view',
            'crm.leads.edit',
            'crm.waitlist.view',
            'crm.waitlist.edit',
            'crm.consultations.view',
            'crm.consultations.edit',
            'crm.group_inquiries.view',
            'crm.group_inquiries.edit',
            'bookings.view',
            'automation.logs.view',
        ]);

        $admin = User::query()->where('email', 'admin@heartwellwellness.com')->first();

        if ($admin && ! $admin->hasRole('super_admin')) {
            $admin->assignRole($superAdmin);
        }
    }
}
