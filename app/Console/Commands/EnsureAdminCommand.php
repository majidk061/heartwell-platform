<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class EnsureAdminCommand extends Command
{
    protected $signature = 'heartwell:ensure-admin
                            {--email=admin@heartwellwellness.com : Admin email address}
                            {--password=password : Plain-text password (hashed on save)}';

    protected $description = 'Create or reset the HeartWell admin user for Filament login';

    public function handle(): int
    {
        $email = (string) $this->option('email');
        $password = (string) $this->option('password');

        $user = User::query()->firstOrNew(['email' => $email]);
        $user->name = $user->exists ? $user->name : 'HeartWell Admin';
        $user->password = $password;
        $user->email_verified_at = $user->email_verified_at ?? now();
        $user->is_active = true;
        $user->save();

        $role = Role::query()->firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);

        if (! $user->hasRole($role)) {
            $user->assignRole($role);
        }

        $verified = Hash::check($password, $user->fresh()->password);

        if (! $verified) {
            $this->error('Admin user saved but password verification failed. Check the User model password cast.');

            return self::FAILURE;
        }

        $this->info("Admin ready: {$email}");

        return self::SUCCESS;
    }
}
