<?php

namespace App\Filament\Pages;

use App\Domains\Content\Models\SiteSetting;
use App\Filament\Concerns\AuthorizesWithPermissions;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ManageEmailNotifications extends Page implements HasForms
{
    use AuthorizesWithPermissions;
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-bell-alert';

    protected static ?string $navigationGroup = 'System Settings';

    protected static ?string $navigationLabel = 'Email Notifications';

    protected static ?int $navigationSort = 5;

    protected static string $view = 'filament.pages.manage-email-notifications';

    protected static ?string $title = 'Email Notification Recipients';

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    protected static function permissionPrefix(): string
    {
        return 'system.email_notifications';
    }

    public function getSubheading(): ?string
    {
        return 'Set which admin email addresses receive alerts for each form type. Leave blank to use the global fallback from Email / SMTP settings.';
    }

    public function mount(): void
    {
        $stored = SiteSetting::query()->where('key', 'email_notifications')->value('value') ?? [];

        $this->form->fill([
            'waitlist_admin_emails' => $stored['waitlist_admin_emails'] ?? [],
            'consultation_admin_emails' => $stored['consultation_admin_emails'] ?? [],
            'group_inquiry_admin_emails' => $stored['group_inquiry_admin_emails'] ?? [],
            'booking_admin_emails' => $stored['booking_admin_emails'] ?? [],
            'new_lead_admin_emails' => $stored['new_lead_admin_emails'] ?? [],
            'default_admin_emails' => $stored['default_admin_emails'] ?? [],
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TagsInput::make('default_admin_emails')
                    ->label('Global fallback emails')
                    ->helperText('Used when a specific form type has no recipients set.'),
                Forms\Components\TagsInput::make('waitlist_admin_emails')->label('Waitlist submissions'),
                Forms\Components\TagsInput::make('consultation_admin_emails')->label('Consultation requests'),
                Forms\Components\TagsInput::make('group_inquiry_admin_emails')->label('Group inquiries'),
                Forms\Components\TagsInput::make('booking_admin_emails')->label('Acuity bookings'),
                Forms\Components\TagsInput::make('new_lead_admin_emails')->label('New CRM leads'),
            ])
            ->statePath('data');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('save')
                ->label('Save recipients')
                ->action('save'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        SiteSetting::query()->updateOrCreate(
            ['key' => 'email_notifications'],
            ['value' => $data],
        );

        Notification::make()->title('Notification recipients saved')->success()->send();
    }
}
