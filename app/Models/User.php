<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Support\OwnerAccess;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasAnyRole(['super_admin', 'admin']);
    }

    public function canAccessAdmin(): bool
    {
        return $this->hasAnyRole(['super_admin', 'store_owner', 'admin']);
    }

    public function canAccessIdentity(): bool
    {
        return $this->isSuperAdmin()
            || ($this->hasRole('store_owner') && OwnerAccess::enabled(OwnerAccess::IDENTITY));
    }

    public function canAccessContact(): bool
    {
        return $this->isSuperAdmin()
            || ($this->hasRole('store_owner') && OwnerAccess::enabled(OwnerAccess::CONTACT));
    }

    public function canAccessSiteSetting(): bool
    {
        return $this->isSuperAdmin()
            || ($this->hasRole('store_owner') && OwnerAccess::enabled(OwnerAccess::SITE_SETTING));
    }

    public function canAccessSeo(): bool
    {
        return $this->isSuperAdmin()
            || ($this->hasRole('store_owner') && OwnerAccess::enabled(OwnerAccess::SEO));
    }

    public function canAccessPayment(): bool
    {
        return $this->isSuperAdmin()
            || ($this->hasRole('store_owner') && OwnerAccess::enabled(OwnerAccess::PAYMENT));
    }

    public function canAccessSettingsPage(): bool
    {
        return $this->canAccessIdentity()
            || $this->canAccessContact()
            || $this->canAccessSeo()
            || $this->canAccessPayment();
    }
}
