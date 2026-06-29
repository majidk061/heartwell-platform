<?php

namespace App\Filament\Pages;

use App\Domains\Integrations\Services\AcuityService;
use App\Domains\Integrations\Services\MailchimpService;
use App\Domains\Integrations\Services\SendGridService;
use App\Domains\Integrations\Services\SettingsResolver;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ManageIntegrations extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-puzzle-piece';

    protected static ?string $navigationGroup = 'System Settings';

    protected static ?string $navigationLabel = 'Integrations';

    protected static ?int $navigationSort = 2;

    protected static string $view = 'filament.pages.manage-integrations';

    protected static ?string $title = 'Integrations';

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public static function canAccess(): bool
    {
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        if (! \Illuminate\Support\Facades\Schema::hasTable('roles')) {
            return true;
        }

        return $user->hasRole('super_admin') || $user->can('system.integrations.manage');
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
            'acuity_enabled' => (bool) ($r->get('acuity_enabled') ?? config('integrations.acuity.enabled')),
            'acuity_user_id' => $r->get('acuity_user_id', 'ACUITY_USER_ID'),
            'acuity_api_key' => '',
            'acuity_webhook_secret' => '',
            'acuity_embed_url' => $r->get('acuity_embed_url', 'ACUITY_EMBED_URL'),
            'mailchimp_enabled' => (bool) ($r->get('mailchimp_enabled') ?? config('integrations.mailchimp.enabled')),
            'mailchimp_api_key' => '',
            'mailchimp_server_prefix' => $r->get('mailchimp_server_prefix', 'MAILCHIMP_SERVER_PREFIX'),
            'mailchimp_audience_id' => $r->get('mailchimp_audience_id', 'MAILCHIMP_AUDIENCE_ID'),
            'sendgrid_enabled' => (bool) ($r->get('sendgrid_enabled') ?? config('integrations.sendgrid.enabled')),
            'sendgrid_api_key' => '',
            'sendgrid_from_email' => $r->get('sendgrid_from_email', 'SENDGRID_FROM_EMAIL'),
            'sendgrid_from_name' => $r->get('sendgrid_from_name', 'SENDGRID_FROM_NAME'),
            'sendgrid_template_waitlist' => $r->get('sendgrid_template_waitlist', 'SENDGRID_TEMPLATE_WAITLIST_WELCOME'),
            'sendgrid_template_consultation' => $r->get('sendgrid_template_consultation', 'SENDGRID_TEMPLATE_CONSULTATION_ACK'),
            'sendgrid_template_booking' => $r->get('sendgrid_template_booking', 'SENDGRID_TEMPLATE_BOOKING_CONFIRMATION'),
            'hydreight_enabled' => (bool) ($r->get('hydreight_enabled') ?? config('integrations.hydreight.enabled')),
            'hydreight_portal_url' => $r->get('hydreight_portal_url', 'HYDREIGHT_PORTAL_URL'),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Tabs::make('Integrations')->tabs([
                Forms\Components\Tabs\Tab::make('Acuity')->schema([
                    Forms\Components\Toggle::make('acuity_enabled')->label('Enable Acuity booking'),
                    Forms\Components\TextInput::make('acuity_user_id')->label('User ID'),
                    Forms\Components\TextInput::make('acuity_api_key')->label('API key')->password()->revealable(),
                    Forms\Components\TextInput::make('acuity_webhook_secret')->label('Webhook secret')->password()->revealable(),
                    Forms\Components\TextInput::make('acuity_embed_url')->label('Embed URL')->url()->columnSpanFull(),
                ])->columns(2),
                Forms\Components\Tabs\Tab::make('Mailchimp')->schema([
                    Forms\Components\Toggle::make('mailchimp_enabled')->label('Enable Mailchimp'),
                    Forms\Components\TextInput::make('mailchimp_api_key')->label('API key')->password()->revealable(),
                    Forms\Components\TextInput::make('mailchimp_server_prefix')->label('Server prefix (e.g. us1)'),
                    Forms\Components\TextInput::make('mailchimp_audience_id')->label('Audience / list ID'),
                ])->columns(2),
                Forms\Components\Tabs\Tab::make('SendGrid')->schema([
                    Forms\Components\Toggle::make('sendgrid_enabled')->label('Enable SendGrid'),
                    Forms\Components\TextInput::make('sendgrid_api_key')->label('API key')->password()->revealable(),
                    Forms\Components\TextInput::make('sendgrid_from_email')->label('From email')->email(),
                    Forms\Components\TextInput::make('sendgrid_from_name')->label('From name'),
                    Forms\Components\TextInput::make('sendgrid_template_waitlist')->label('Waitlist template ID'),
                    Forms\Components\TextInput::make('sendgrid_template_consultation')->label('Consultation template ID'),
                    Forms\Components\TextInput::make('sendgrid_template_booking')->label('Booking template ID'),
                ])->columns(2),
                Forms\Components\Tabs\Tab::make('Hydreight')->schema([
                    Forms\Components\Toggle::make('hydreight_enabled')->label('Enable clinical portal link'),
                    Forms\Components\TextInput::make('hydreight_portal_url')->label('Portal URL')->url()->columnSpanFull(),
                ]),
            ])->columnSpanFull(),
        ])->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $r = app(SettingsResolver::class);
        $userId = auth()->id();

        $plain = [
            'acuity_enabled' => $data['acuity_enabled'] ? '1' : '0',
            'acuity_user_id' => $data['acuity_user_id'] ?? null,
            'acuity_embed_url' => $data['acuity_embed_url'] ?? null,
            'mailchimp_enabled' => $data['mailchimp_enabled'] ? '1' : '0',
            'mailchimp_server_prefix' => $data['mailchimp_server_prefix'] ?? null,
            'mailchimp_audience_id' => $data['mailchimp_audience_id'] ?? null,
            'sendgrid_enabled' => $data['sendgrid_enabled'] ? '1' : '0',
            'sendgrid_from_email' => $data['sendgrid_from_email'] ?? null,
            'sendgrid_from_name' => $data['sendgrid_from_name'] ?? null,
            'sendgrid_template_waitlist' => $data['sendgrid_template_waitlist'] ?? null,
            'sendgrid_template_consultation' => $data['sendgrid_template_consultation'] ?? null,
            'sendgrid_template_booking' => $data['sendgrid_template_booking'] ?? null,
            'hydreight_enabled' => $data['hydreight_enabled'] ? '1' : '0',
            'hydreight_portal_url' => $data['hydreight_portal_url'] ?? null,
        ];

        foreach ($plain as $key => $value) {
            if ($value !== null) {
                $r->set($key, (string) $value, 'integrations', $userId);
            }
        }

        foreach (['acuity_api_key', 'acuity_webhook_secret', 'mailchimp_api_key', 'sendgrid_api_key'] as $secret) {
            if (filled($data[$secret] ?? null)) {
                $r->set($secret, $data[$secret], 'integrations', $userId);
            }
        }

        $r->mergeIntoConfig();

        Notification::make()->title('Integration settings saved')->success()->send();
    }

    public function testMailchimp(): void
    {
        $this->save();

        if (app(MailchimpService::class)->isConfigured()) {
            Notification::make()->title('Mailchimp configured')->success()->send();
        } else {
            Notification::make()->title('Mailchimp not configured')->warning()->send();
        }
    }

    public function testSendGrid(): void
    {
        $this->save();

        if (app(SendGridService::class)->isConfigured()) {
            Notification::make()->title('SendGrid configured')->success()->send();
        } else {
            Notification::make()->title('SendGrid not configured')->warning()->send();
        }
    }

    public function testAcuity(): void
    {
        $this->save();

        if (app(AcuityService::class)->isConfigured()) {
            Notification::make()->title('Acuity configured')->success()->send();
        } else {
            Notification::make()->title('Acuity not configured')->warning()->send();
        }
    }
}
