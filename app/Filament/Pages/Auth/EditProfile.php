<?php

namespace App\Filament\Pages\Auth;

use App\Filament\Concerns\ConfiguresCmsImageFields;
use App\Filament\Concerns\HandlesCmsImageUploads;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;
use Filament\Support\Exceptions\Halt;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Throwable;

/**
 * @property Form $personalForm
 * @property Form $securityForm
 */
class EditProfile extends BaseEditProfile
{
    use ConfiguresCmsImageFields;
    use HandlesCmsImageUploads;

    protected static string $view = 'filament.pages.auth.edit-profile';

    /** @var array<string, mixed>|null */
    public ?array $personalData = [];

    /** @var array<string, mixed>|null */
    public ?array $securityData = [];

    public string $activeTab = 'personal';

    public function form(Form $form): Form
    {
        return $form;
    }

    public function mount(): void
    {
        $this->fillPersonalForm();
        $this->resetSecurityForm();
    }

    public function setActiveTab(string $tab): void
    {
        if (in_array($tab, ['personal', 'security'], true)) {
            $this->activeTab = $tab;
        }
    }

    public function getTitle(): string|Htmlable
    {
        return 'My Profile';
    }

    public function getSubheading(): string|Htmlable|null
    {
        return 'Manage your account details and password.';
    }

    /**
     * @return array<int, \Filament\Actions\Action|\Filament\Actions\ActionGroup>
     */
    protected function getFormActions(): array
    {
        return [];
    }

    /**
     * @return array<int | string, string | Form>
     */
    protected function getForms(): array
    {
        return [
            'personalForm' => $this->makePersonalForm(),
            'securityForm' => $this->makeSecurityForm(),
        ];
    }

    protected function makePersonalForm(): Form
    {
        return $this->makeForm()
            ->schema([
                static::cmsImageUploadField(
                    'avatar_path',
                    'Profile photo',
                    'cms/users',
                    'Square JPG or PNG, recommended 400×400.',
                )
                    ->imageEditor()
                    ->imageEditorAspectRatios(['1:1'])
                    ->avatar()
                    ->alignCenter()
                    ->columnSpanFull(),
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
            ])
            ->operation('edit')
            ->model($this->getUser())
            ->statePath('personalData')
            ->inlineLabel(false);
    }

    protected function makeSecurityForm(): Form
    {
        return $this->makeForm()
            ->schema([
                TextInput::make('current_password')
                    ->label('Current password')
                    ->password()
                    ->revealable(filament()->arePasswordsRevealable())
                    ->autocomplete('current-password')
                    ->dehydrated(false)
                    ->required(fn (Get $get): bool => filled($get('password')) || filled($get('passwordConfirmation'))),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ])
            ->statePath('securityData')
            ->inlineLabel(false);
    }

    public function savePersonal(): void
    {
        try {
            $this->beginDatabaseTransaction();

            $data = $this->personalForm->getState();
            $data = $this->normalizeCmsImageFields($data, ['avatar_path']);

            $this->getUser()->update([
                'name' => $data['name'],
                'email' => $data['email'],
                'avatar_path' => $data['avatar_path'] ?? null,
            ]);

            $this->commitDatabaseTransaction();
        } catch (Halt $exception) {
            $exception->shouldRollbackDatabaseTransaction()
                ? $this->rollBackDatabaseTransaction()
                : $this->commitDatabaseTransaction();

            return;
        } catch (Throwable $exception) {
            $this->rollBackDatabaseTransaction();

            throw $exception;
        }

        $this->fillPersonalForm();

        Notification::make()
            ->title('Personal details saved')
            ->success()
            ->send();
    }

    public function saveSecurity(): void
    {
        try {
            $this->beginDatabaseTransaction();

            $data = $this->securityForm->getState();

            if (filled($data['password'] ?? null)) {
                $currentPassword = $data['current_password'] ?? null;

                if (! filled($currentPassword) || ! Hash::check($currentPassword, $this->getUser()->password)) {
                    throw ValidationException::withMessages([
                        'securityData.current_password' => 'The current password is incorrect.',
                    ]);
                }

                $this->getUser()->update([
                    'password' => $data['password'],
                ]);

                if (request()->hasSession()) {
                    request()->session()->put([
                        'password_hash_'.filament()->getAuthGuard() => $this->getUser()->fresh()->password,
                    ]);
                }
            } elseif (blank($data['password'] ?? null)) {
                throw ValidationException::withMessages([
                    'securityData.password' => 'Enter a new password to save security changes.',
                ]);
            }

            $this->commitDatabaseTransaction();
        } catch (Halt $exception) {
            $exception->shouldRollbackDatabaseTransaction()
                ? $this->rollBackDatabaseTransaction()
                : $this->commitDatabaseTransaction();

            return;
        } catch (Throwable $exception) {
            $this->rollBackDatabaseTransaction();

            throw $exception;
        }

        $this->resetSecurityForm();

        Notification::make()
            ->title('Password updated')
            ->success()
            ->send();
    }

    public function cancelPersonal(): void
    {
        $this->fillPersonalForm();

        Notification::make()
            ->title('Changes discarded')
            ->body('Personal details were reset to the last saved version.')
            ->info()
            ->send();
    }

    public function cancelSecurity(): void
    {
        $this->resetSecurityForm();

        Notification::make()
            ->title('Changes discarded')
            ->body('Password fields were cleared.')
            ->info()
            ->send();
    }

    protected function fillPersonalForm(): void
    {
        $user = $this->getUser()->fresh();

        $data = [
            'name' => $user->name,
            'email' => $user->email,
            'avatar_path' => $user->avatar_path,
        ];

        $this->personalData = $this->hydrateCmsImageFields($data, ['avatar_path']);
    }

    protected function resetSecurityForm(): void
    {
        $this->securityData = [
            'current_password' => null,
            'password' => null,
            'passwordConfirmation' => null,
        ];
    }

    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->label('New password')
            ->password()
            ->revealable(filament()->arePasswordsRevealable())
            ->rule(Password::default())
            ->autocomplete('new-password')
            ->dehydrated(fn ($state): bool => filled($state))
            ->dehydrateStateUsing(fn ($state): string => $state)
            ->helperText('Leave blank to keep your current password.');
    }

    protected function getPasswordConfirmationFormComponent(): Component
    {
        return TextInput::make('passwordConfirmation')
            ->label('Confirm new password')
            ->password()
            ->revealable(filament()->arePasswordsRevealable())
            ->autocomplete('new-password')
            ->dehydrated(false)
            ->required(fn (Get $get): bool => filled($get('password')))
            ->same('password');
    }
}
