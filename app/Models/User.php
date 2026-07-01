<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserPlan;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'name', 'email', 'password', 'status', 'plan', 'role', 'country',
    'phone', 'phone_verified_at', 'two_factor_enabled',
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

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
            'is_admin' => 'boolean',
            'status' => UserStatus::class,
            'plan' => UserPlan::class,
            'role' => UserRole::class,
            'phone_verified_at' => 'datetime',
            'two_factor_enabled' => 'boolean',
            'last_login_at' => 'datetime',
        ];
    }

    /**
     * Determine if the user has admin privileges.
     */
    public function isAdmin(): bool
    {
        return (bool) $this->is_admin;
    }

    public function loginHistories(): HasMany
    {
        return $this->hasMany(LoginHistory::class)->latest('created_at');
    }

    public function notes(): HasMany
    {
        return $this->hasMany(UserNote::class)->latest();
    }
}
