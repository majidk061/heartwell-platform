<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Domains\Content\Support\CmsImage;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, HasAvatar
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    /** @var list<string> */
    protected $fillable = [
        'name',
        'email',
        'avatar_path',
        'password',
        'is_active',
        'invited_at',
    ];

    /** @var list<string> */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_active !== false;
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return CmsImage::url($this->avatar_path);
    }

    /** @var array<string, string> */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'invited_at' => 'datetime',
        'is_active' => 'boolean',
        'password' => 'hashed',
    ];
}
