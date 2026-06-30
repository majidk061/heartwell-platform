<?php

namespace Tests\Feature;

use App\Domains\Admin\Actions\InviteAdminUserAction;
use App\Domains\Admin\Actions\SendAdminPasswordResetInviteAction;
use App\Domains\Integrations\Models\EmailTemplate;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class AdminInviteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        EmailTemplate::query()->create([
            'key' => 'admin_invite',
            'name' => 'Admin invite',
            'subject' => 'Set your HeartWell admin password',
            'heading' => 'Welcome to HeartWell admin',
            'body' => '<p>Hi {{name}},</p><p>Set your password using this link: <a href="{{reset_url}}">{{reset_url}}</a></p>',
            'button_label' => 'Set your password',
            'button_url' => '{{reset_url}}',
            'is_enabled' => true,
        ]);
    }

    public function test_invite_creates_password_reset_token(): void
    {
        Mail::fake();

        $user = app(InviteAdminUserAction::class)->execute([
            'name' => 'New Editor',
            'email' => 'editor@heartwell.test',
            'roles' => [],
            'permissions' => [],
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => 'editor@heartwell.test',
        ]);

        $this->assertNotNull($user->invited_at);
    }

    public function test_password_reset_invite_email_contains_admin_reset_url(): void
    {
        $user = User::factory()->create([
            'email' => 'reset@heartwell.test',
            'is_active' => true,
        ]);

        app(SendAdminPasswordResetInviteAction::class)->execute($user);

        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => 'reset@heartwell.test',
        ]);

        $resetUrl = Filament::getResetPasswordUrl('test-token-value', $user);

        $this->assertStringContainsString('/admin/password-reset/reset', $resetUrl);
        $this->assertStringContainsString('signature=', $resetUrl);
        $this->assertStringContainsString('email='.urlencode($user->email), $resetUrl);
    }

    public function test_password_reset_page_is_reachable_with_token(): void
    {
        $user = User::factory()->create([
            'email' => 'reachable@heartwell.test',
            'is_active' => true,
        ]);

        $token = Password::broker('users')->createToken($user);

        $this->get(Filament::getResetPasswordUrl($token, $user))->assertOk();
    }
}
