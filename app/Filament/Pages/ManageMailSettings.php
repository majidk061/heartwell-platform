<?php

namespace App\Filament\Pages;

use App\Domains\Integrations\Services\SettingsResolver;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Mail;

class ManageMailSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $navigationGroup = 'System Settings';

    protected static ?string $navigationLabel = 'Email / SMTP';

    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.manage-mail-settings';

    protected static ?string $title = 'Email & SMTP Settings';

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public static function canAccess(): bool
    {
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        if (! \Illuminate\Support\Facades\Schema::hasTable('permissions')) {
            return true;
        }

        return $user->hasRole('super_admin') || $user->can('system.mail.manage');
    }

    public function getSubheading(): ?string
    {
        return 'Configure how the website sends emails. Passwords are stored encrypted.';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label('Back to dashboard')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(Dashboard::getUrl()),
        ];
    }

    public function mount(): void
    {
        $r = app(SettingsResolver::class);

        $this->form->fill([
            'mail_mailer' => $r->get('mail_mailer', 'MAIL_MAILER') ?? 'smtp',
            'mail_host' => $r->get('mail_host', 'MAIL_HOST'),
            'mail_port' => $r->get('mail_port', 'MAIL_PORT') ?? '587',
            'mail_encryption' => $r->get('mail_encryption', 'MAIL_ENCRYPTION') ?? 'tls',
            'mail_username' => $r->get('mail_username', 'MAIL_USERNAME'),
            'mail_password' => '',
            'mail_from_address' => $r->get('mail_from_address', 'MAIL_FROM_ADDRESS'),
            'mail_from_name' => $r->get('mail_from_name', 'MAIL_FROM_NAME'),
            'admin_alert_email' => $r->get('admin_alert_email', 'SENDGRID_ADMIN_ALERT_EMAIL'),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('mail_mailer')
                ->label('Mail driver')
                ->options(['smtp' => 'SMTP', 'log' => 'Log only (testing)'])
                ->required(),
            Forms\Components\TextInput::make('mail_host')->label('SMTP host'),
            Forms\Components\TextInput::make('mail_port')->label('SMTP port')->numeric(),
            Forms\Components\Select::make('mail_encryption')
                ->label('Encryption')
                ->options(['tls' => 'TLS', 'ssl' => 'SSL', '' => 'None']),
            Forms\Components\TextInput::make('mail_username')->label('SMTP username'),
            Forms\Components\TextInput::make('mail_password')
                ->label('SMTP password')
                ->password()
                ->revealable()
                ->helperText('Leave blank to keep the current password.'),
            Forms\Components\TextInput::make('mail_from_address')->label('From email')->email()->required(),
            Forms\Components\TextInput::make('mail_from_name')->label('From name')->required(),
            Forms\Components\TextInput::make('admin_alert_email')
                ->label('Admin alert email')
                ->email()
                ->helperText('Internal notifications for new leads and form submissions.'),
        ])->columns(2)->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $r = app(SettingsResolver::class);
        $userId = auth()->id();

        foreach (['mail_mailer', 'mail_host', 'mail_port', 'mail_encryption', 'mail_username', 'mail_from_address', 'mail_from_name', 'admin_alert_email'] as $key) {
            if (filled($data[$key] ?? null)) {
                $r->set($key, (string) $data[$key], 'mail', $userId);
            }
        }

        if (filled($data['mail_password'] ?? null)) {
            $r->set('mail_password', $data['mail_password'], 'mail', $userId);
        }

        app(SettingsResolver::class)->mergeIntoConfig();

        Notification::make()->title('Email settings saved')->success()->send();
    }

    public function testEmail(): void
    {
        $this->save();
        $to = auth()->user()?->email;

        if (! $to) {
            Notification::make()->title('No admin email on your account')->danger()->send();

            return;
        }

        try {
            Mail::raw('HeartWell test email — your SMTP settings are working.', function ($message) use ($to) {
                $message->to($to)->subject('HeartWell SMTP Test');
            });

            Notification::make()->title('Test email sent to '.$to)->success()->send();
        } catch (\Throwable $e) {
            Notification::make()->title('Test failed')->body($e->getMessage())->danger()->send();
        }
    }
}
